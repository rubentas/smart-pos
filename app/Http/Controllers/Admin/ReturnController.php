<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Returns;
use App\Models\Sale;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
  /**
   * Display a listing of returns.
   */
  public function index(Request $request)
  {
    $query = Returns::with('sale', 'user', 'details.product')
      ->whereHas('sale', function ($q) {
        $q->where('branch_id', session('active_branch'));
      })
      ->orderBy('created_at', 'desc');

    // Filter by date range
    if ($request->filled('start_date') && $request->filled('end_date')) {
      $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    // Filter by return no
    if ($request->filled('return_no')) {
      $query->where('return_no', 'like', '%' . $request->return_no . '%');
    }

    $returns = $query->paginate(15);

    return view('admin.returns.index', compact('returns'));
  }

  /**
   * Show form to create return (select sale)
   */
  public function create()
  {
    $sales = Sale::with('user', 'details.product')
      ->where('branch_id', session('active_branch'))
      ->whereDate('created_at', today())
      ->orderBy('created_at', 'desc')
      ->get();

    return view('admin.returns.create', compact('sales'));
  }

  /**
   * Show form to return items from specific sale
   */
  public function createFromSale($saleId)
  {
    $sale = Sale::with('details.product', 'user')
      ->where('branch_id', session('active_branch'))
      ->findOrFail($saleId);

    // Cek apakah sudah pernah diretur?
    $existingReturns = Returns::where('sale_id', $saleId)->get();
    $returnedQuantities = [];

    foreach ($existingReturns as $return) {
      foreach ($return->details as $detail) {
        $key = $detail->sale_detail_id;
        if (!isset($returnedQuantities[$key])) {
          $returnedQuantities[$key] = 0;
        }
        $returnedQuantities[$key] += $detail->quantity;
      }
    }

    return view('admin.returns.create-form', compact('sale', 'returnedQuantities'));
  }

  /**
   * Store a newly created return.
   */
  public function store(Request $request)
  {
    $request->validate([
      'sale_id' => 'required|exists:sales,id',
      'reason' => 'required|string|min:5',
      'items' => 'required|array|min:1',
      'items.*.sale_detail_id' => 'required|exists:sale_details,id',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1',
      'items.*.price' => 'required|numeric|min:0',
      'items.*.max_qty' => 'required|integer|min:1',
    ]);

    // Validasi quantity tidak melebihi max
    foreach ($request->items as $item) {
      if ($item['quantity'] > $item['max_qty']) {
        return response()->json([
          'success' => false,
          'message' => 'Quantity retur melebihi quantity yang bisa diretur!'
        ], 422);
      }
    }

    // Generate return number
    $returnNo = $this->generateReturnNo();

    // Hitung total refund
    $totalRefund = collect($request->items)->sum(function ($item) {
      return $item['quantity'] * $item['price'];
    });

    DB::beginTransaction();

    try {
      // Create return
      $return = Returns::create([
        'return_no' => $returnNo,
        'sale_id' => $request->sale_id,
        'user_id' => Auth::id(),
        'total_refund' => $totalRefund,
        'reason' => $request->reason,
        'date' => now()->toDateString(),
      ]);

      // Create return details and update stock
      foreach ($request->items as $item) {
        // Skip if quantity 0
        if ($item['quantity'] <= 0) continue;

        // Create return detail
        $return->details()->create([
          'sale_detail_id' => $item['sale_detail_id'],
          'product_id' => $item['product_id'],
          'quantity' => $item['quantity'],
          'price' => $item['price'],
          'subtotal' => $item['quantity'] * $item['price'],
        ]);

        // Update product stock
        $product = Product::find($item['product_id']);
        $oldStock = $product->stock;
        $product->stock += $item['quantity'];
        $product->save();

        // Create stock log
        StockLog::create([
          'product_id' => $item['product_id'],
          'type' => 'RETURN',
          'quantity' => $item['quantity'],
          'old_stock' => $oldStock,
          'new_stock' => $product->stock,
          'reference' => 'Return: ' . $returnNo,
          'created_by' => Auth::id(),
        ]);
      }

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Retur berhasil diproses!',
        'return_id' => $return->id,
        'return_no' => $return->return_no
      ]);
    } catch (\Exception $e) {
      DB::rollBack();

      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified return.
   */
  public function show($id)
  {
    $return = Returns::with('sale', 'user', 'details.product', 'sale.user')
      ->findOrFail($id);

    return view('admin.returns.show', compact('return'));
  }

  /**
   * Generate return number
   */
  private function generateReturnNo()
  {
    $date = now()->format('Ymd');
    $lastReturn = Returns::whereDate('created_at', today())
      ->orderBy('id', 'desc')
      ->first();

    if ($lastReturn) {
      $lastNumber = intval(substr($lastReturn->return_no, -4));
      $newNumber = $lastNumber + 1;
    } else {
      $newNumber = 1;
    }

    return 'RTR-' . $date . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
  }
}