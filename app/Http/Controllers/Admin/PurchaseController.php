<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\StockLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
  public function index()
  {
    $purchases = Purchase::with('supplier')
      ->where('branch_id', Session::get('active_branch'))
      ->latest()
      ->paginate(15);
    return view('admin.purchases.index', compact('purchases'));
  }

  public function create()
  {
    $suppliers = Supplier::all();
    $products = Product::where('branch_id', session('active_branch'))->get();
    return view('admin.purchases.create', compact('suppliers', 'products'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'invoice_no' => 'required|unique:purchases',
      'supplier_id' => 'required|exists:suppliers,id',
      'date' => 'required|date',
      'products' => 'required|json',
      'total' => 'required|numeric',
      'notes' => 'nullable|string',
    ]);

    $products = json_decode($request->products, true);

    DB::transaction(function () use ($request, $products) {
      // Simpan purchase
      $purchase = Purchase::create([
        'branch_id' => session('active_branch'),
        'invoice_no' => $request->invoice_no,
        'supplier_id' => $request->supplier_id,
        'user_id' => Auth::id(),
        'date' => $request->date,
        'total' => $request->total,
        'notes' => $request->notes,
      ]);

      // Simpan detail dan update stok
      foreach ($products as $item) {
        $product = Product::find($item['id']);

        // Simpan detail
        PurchaseDetail::create([
          'purchase_id' => $purchase->id,
          'product_id' => $item['id'],
          'quantity' => $item['quantity'],
          'purchase_price' => $item['price'],
          'subtotal' => $item['price'] * $item['quantity'],
        ]);

        // Update stok
        $stockBefore = $product->stock;
        $product->stock += $item['quantity'];
        $product->save();

        // Catat log stok
        StockLog::create([
          'product_id' => $item['id'],
          'type' => 'IN',
          'quantity' => $item['quantity'],
          'stock_before' => $stockBefore,
          'stock_after' => $product->stock,
          'reference_type' => 'Purchase',
          'reference_id' => $purchase->id,
          'description' => 'Pembelian dari supplier',
          'user_id' => Auth::id(),
        ]);
      }
    });

    return redirect()->route('admin.purchases.index')
      ->with('success', 'Pembelian berhasil disimpan');
  }

  public function show(Purchase $purchase)
  {
    $purchase->load(['supplier', 'user', 'details.product']);
    return view('admin.purchases.show', compact('purchase'));
  }

  public function destroy(Purchase $purchase)
  {
    DB::transaction(function () use ($purchase) {
      // Hapus detail (otomatis kena onDelete cascade)
      $purchase->delete();
    });

    return redirect()->route('admin.purchases.index')
      ->with('success', 'Pembelian berhasil dihapus');
  }
}