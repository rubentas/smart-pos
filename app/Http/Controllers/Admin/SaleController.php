<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $query = Sale::with('user', 'details.product')
      ->orderBy('created_at', 'desc');

    // Filter by date range
    if ($request->filled('start_date') && $request->filled('end_date')) {
      $query->whereBetween('date', [$request->start_date, $request->end_date]);
    } elseif ($request->filled('start_date')) {
      $query->where('date', '>=', $request->start_date);
    } elseif ($request->filled('end_date')) {
      $query->where('date', '<=', $request->end_date);
    }

    // Filter by invoice
    if ($request->filled('invoice')) {
      $query->where('invoice_no', 'like', '%' . $request->invoice . '%');
    }

    // Filter by payment method
    if ($request->filled('payment_method')) {
      $query->where('payment_method', $request->payment_method);
    }

    $sales = $query->paginate(15);

    return view('admin.sales.index', compact('sales'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Ambil produk dengan stok > 0 untuk ditampilkan di POS
    $products = Product::where('stock', '>', 0)
      ->with('category')
      ->orderBy('name')
      ->get();

    return view('admin.sales.create', compact('products'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validasi request
    $request->validate([
      'cart' => 'required|array|min:1',
      'cart.*.product_id' => 'required|exists:products,id',
      'cart.*.quantity' => 'required|integer|min:1',
      'cart.*.price' => 'required|numeric|min:0',
      'cart.*.subtotal' => 'required|numeric|min:0',
      'discount' => 'nullable|numeric|min:0',
      'payment_method' => 'required|in:cash,transfer,qris',
    ]);

    $subtotal = collect($request->cart)->sum('subtotal');

    $discount = $request->discount ?? 0;

    $tax = ($subtotal - $discount) * 0.11;

    $total = $subtotal - $discount + $tax;


    $invoiceNo = $this->generateInvoiceNo();

    // Validasi stok sebelum transaksi
    foreach ($request->cart as $item) {
      $product = Product::find($item['product_id']);
      if ($product->stock < $item['quantity']) {
        return response()->json([
          'success' => false,
          'message' => "Stok {$product->name} tidak mencukupi! Tersedia: {$product->stock}"
        ], 422);
      }
    }

    // Mulai database transaction
    DB::beginTransaction();

    try {
      // Simpan ke tabel sales
      $sale = Sale::create([
        'invoice_no' => $invoiceNo,
        'user_id' => Auth::id(),
        'subtotal' => $subtotal,
        'discount' => $discount,
        'tax' => $tax,
        'total' => $total,
        'payment_method' => $request->payment_method,
        'payment_status' => 'paid',
        'date' => now()->toDateString(),
      ]);

      // Simpan detail penjualan dan update stok
      foreach ($request->cart as $item) {
        $sale->details()->create([
          'product_id' => $item['product_id'],
          'quantity' => $item['quantity'],
          'selling_price' => $item['price'],
          'subtotal' => $item['subtotal'],
        ]);

        // Update stok produk
        $product = Product::find($item['product_id']);
        $oldStock = $product->stock;
        $product->stock -= $item['quantity'];
        $product->save();

        // Catat stock log
        StockLog::create([
          'product_id' => $item['product_id'],
          'type' => 'OUT',
          'quantity' => $item['quantity'],
          'old_stock' => $oldStock,
          'new_stock' => $product->stock,
          'reference' => 'Sale: ' . $invoiceNo,
          'created_by' => Auth::id(),
        ]);
      }

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Transaksi berhasil disimpan!',
        'sale_id' => $sale->id,
        'invoice_no' => $sale->invoice_no
      ]);
    } catch (\Exception $e) {
      // Rollback jika ada error
      DB::rollBack();

      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $sale = Sale::with('user', 'details.product', 'details.product.category')
      ->findOrFail($id);

    return view('admin.sales.show', compact('sale'));
  }

  /**
   * Generate invoice PDF
   */
  public function invoice($id)
  {
    $sale = Sale::with('user', 'details.product')
      ->findOrFail($id);

    return view('admin.sales.invoice', compact('sale'));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    abort(404);
  }

  /**
   * Generate nomor invoice otomatis
   */
  private function generateInvoiceNo()
  {
    $date = now()->format('Ymd');
    $lastSale = Sale::whereDate('created_at', today())
      ->orderBy('id', 'desc')
      ->first();

    if ($lastSale) {
      $lastNumber = intval(substr($lastSale->invoice_no, -4));
      $newNumber = $lastNumber + 1;
    } else {
      $newNumber = 1;
    }

    return 'INV-' . $date . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
  }
}