<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
  /**
   * Dashboard Laporan
   */
  public function index()
  {
    return view('admin.reports.index');
  }

  /**
   * Laporan Penjualan
   */
  public function sales(Request $request)
  {
    $request->validate([
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $startDate = $request->start_date;
    $endDate = $request->end_date;

    // Data penjualan
    $sales = Sale::with('user', 'details.product')
      ->where('branch_id', session('active_branch'))
      ->whereBetween('date', [$startDate, $endDate])
      ->orderBy('date', 'desc')
      ->get();

    // Ringkasan
    $totalSales = $sales->count();
    $totalRevenue = $sales->sum('total');
    $totalItems = $sales->sum(function ($sale) {
      return $sale->details->sum('quantity');
    });

    // Grafik harian
    $dailySales = Sale::where('branch_id', session('active_branch'))
      ->whereBetween('date', [$startDate, $endDate])
      ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(total) as total'))
      ->groupBy('date')
      ->orderBy('date')
      ->get();

    // Metode pembayaran
    $paymentMethods = Sale::where('branch_id', session('active_branch'))
      ->whereBetween('date', [$startDate, $endDate])
      ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
      ->groupBy('payment_method')
      ->get();

    // Produk terlaris
    $topProducts = DB::table('sale_details')
      ->join('products', 'sale_details.product_id', '=', 'products.id')
      ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
      ->where('sales.branch_id', session('active_branch'))
      ->whereBetween('sales.date', [$startDate, $endDate])
      ->select('products.name', DB::raw('SUM(sale_details.quantity) as total_qty'), DB::raw('SUM(sale_details.subtotal) as total_sales'))
      ->groupBy('products.id', 'products.name')
      ->orderBy('total_qty', 'desc')
      ->limit(10)
      ->get();

    if ($request->export == 'pdf') {
      $pdf = Pdf::loadView('admin.reports.pdf.sales', compact(
        'startDate',
        'endDate',
        'sales',
        'totalSales',
        'totalRevenue',
        'totalItems',
        'dailySales',
        'paymentMethods',
        'topProducts'
      ));
      return $pdf->download('laporan-penjualan-' . $startDate . '-' . $endDate . '.pdf');
    }

    return view('admin.reports.sales', compact(
      'startDate',
      'endDate',
      'sales',
      'totalSales',
      'totalRevenue',
      'totalItems',
      'dailySales',
      'paymentMethods',
      'topProducts'
    ));
  }

  /**
   * Laporan Pembelian
   */
  public function purchases(Request $request)
  {
    $request->validate([
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $startDate = $request->start_date;
    $endDate = $request->end_date;

    $purchases = Purchase::with('supplier', 'details.product')
      ->whereBetween('date', [$startDate, $endDate])
      ->orderBy('date', 'desc')
      ->get();

    $totalPurchases = $purchases->count();
    $totalSpent = $purchases->sum('total');
    $totalItems = $purchases->sum(function ($purchase) {
      return $purchase->details->sum('quantity');
    });

    $supplierSummary = Purchase::whereBetween('date', [$startDate, $endDate])
      ->select('supplier_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
      ->with('supplier')
      ->groupBy('supplier_id')
      ->get();

    if ($request->export == 'pdf') {
      $pdf = Pdf::loadView('admin.reports.pdf.purchases', compact(
        'startDate',
        'endDate',
        'purchases',
        'totalPurchases',
        'totalSpent',
        'totalItems',
        'supplierSummary'
      ));
      return $pdf->download('laporan-pembelian-' . $startDate . '-' . $endDate . '.pdf');
    }

    return view('admin.reports.purchases', compact(
      'startDate',
      'endDate',
      'purchases',
      'totalPurchases',
      'totalSpent',
      'totalItems',
      'supplierSummary'
    ));
  }

  /**
   * Laporan Stok
   */
  public function stock(Request $request)
  {
    $products = Product::with('category', 'supplier')
      ->orderBy('stock', 'asc')
      ->get();

    $totalProducts = $products->count();
    $totalStock = $products->sum('stock');
    $lowStock = $products->filter(function ($product) {
      return $product->stock <= $product->min_stock;
    })->count();
    $outOfStock = $products->where('stock', 0)->count();

    // Mutasi stok
    $stockLogs = StockLog::with('product', 'user')
      ->when($request->filled('start_date'), function ($q) use ($request) {
        return $q->whereDate('created_at', '>=', $request->start_date);
      })
      ->when($request->filled('end_date'), function ($q) use ($request) {
        return $q->whereDate('created_at', '<=', $request->end_date);
      })
      ->orderBy('created_at', 'desc')
      ->limit(100)
      ->get();

    if ($request->export == 'pdf') {
      $pdf = Pdf::loadView('admin.reports.pdf.stock', compact(
        'products',
        'totalProducts',
        'totalStock',
        'lowStock',
        'outOfStock',
        'stockLogs'
      ));
      return $pdf->download('laporan-stok-' . now()->format('Ymd') . '.pdf');
    }

    return view('admin.reports.stock', compact(
      'products',
      'totalProducts',
      'totalStock',
      'lowStock',
      'outOfStock',
      'stockLogs'
    ));
  }

  /**
   * Laporan Laba Rugi
   */
  public function profitLoss(Request $request)
  {
    $request->validate([
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $startDate = $request->start_date;
    $endDate = $request->end_date;

    // Total Penjualan
    $totalSales = Sale::whereBetween('date', [$startDate, $endDate])->sum('total');

    // HPP (Harga Pokok Penjualan)
    $saleDetails = DB::table('sale_details')
      ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
      ->join('products', 'sale_details.product_id', '=', 'products.id')
      ->whereBetween('sales.date', [$startDate, $endDate])
      ->select(
        DB::raw('SUM(sale_details.quantity * products.purchase_price) as hpp'),
        DB::raw('SUM(sale_details.subtotal) as revenue')
      )
      ->first();

    $hpp = $saleDetails->hpp ?? 0;
    $revenue = $saleDetails->revenue ?? 0;
    $grossProfit = $revenue - $hpp;

    // Total Pembelian (untuk pengeluaran)
    $totalPurchases = Purchase::whereBetween('date', [$startDate, $endDate])->sum('total');

    // Laba Bersih (estimasi)
    $netProfit = $grossProfit - $totalPurchases;

    // Data grafik
    $dailyProfit = DB::table('sales')
      ->leftJoin('sale_details', 'sales.id', '=', 'sale_details.sale_id')
      ->leftJoin('products', 'sale_details.product_id', '=', 'products.id')
      ->whereBetween('sales.date', [$startDate, $endDate])
      ->select(
        DB::raw('DATE(sales.date) as date'),
        DB::raw('SUM(sale_details.quantity * products.purchase_price) as hpp'),
        DB::raw('SUM(sale_details.subtotal) as revenue')
      )
      ->groupBy('date')
      ->orderBy('date')
      ->get();

    if ($request->export == 'pdf') {
      $pdf = Pdf::loadView('admin.reports.pdf.profit-loss', compact(
        'startDate',
        'endDate',
        'totalSales',
        'hpp',
        'revenue',
        'grossProfit',
        'totalPurchases',
        'netProfit',
        'dailyProfit'
      ));
      return $pdf->download('laporan-lab rugi-' . $startDate . '-' . $endDate . '.pdf');
    }

    return view('admin.reports.profit-loss', compact(
      'startDate',
      'endDate',
      'totalSales',
      'hpp',
      'revenue',
      'grossProfit',
      'totalPurchases',
      'netProfit',
      'dailyProfit'
    ));
  }
}