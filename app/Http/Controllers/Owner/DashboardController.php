<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    // Statistik Global (semua cabang)
    $totalBranches = Branch::count();
    $totalUsers = User::count();
    $totalProducts = Product::count();

    // Penjualan Hari Ini (semua cabang)
    $todaySales = Sale::whereDate('date', today())->sum('total');
    $todayTransactions = Sale::whereDate('date', today())->count();

    // Penjualan Bulan Ini
    $monthSales = Sale::whereMonth('date', now()->month)
      ->whereYear('date', now()->year)
      ->sum('total');

    // Pembelian Bulan Ini
    $monthPurchases = Purchase::whereMonth('date', now()->month)
      ->whereYear('date', now()->year)
      ->sum('total');

    // Laba Kotor (estimasi)
    $monthProfit = $monthSales - $monthPurchases;

    // Grafik penjualan per cabang (7 hari terakhir)
    $branchSales = Branch::with(['sales' => function ($q) {
      $q->whereBetween('date', [now()->subDays(6), now()])
        ->select('branch_id', DB::raw('DATE(date) as date'), DB::raw('SUM(total) as total'))
        ->groupBy('branch_id', 'date');
    }])->get();

    // Top Cabang
    $topBranches = Branch::withSum(['sales' => function ($q) {
      $q->whereMonth('date', now()->month);
    }], 'total')
      ->orderBy('sales_sum_total', 'desc')
      ->take(5)
      ->get();

    // Stok hampir habis (semua cabang)
    $lowStock = Product::with('branch')
      ->whereColumn('stock', '<=', 'min_stock')
      ->where('stock', '>', 0)
      ->orderBy('stock', 'asc')
      ->take(10)
      ->get();

    return view('owner.dashboard', compact(
      'totalBranches',
      'totalUsers',
      'totalProducts',
      'todaySales',
      'todayTransactions',
      'monthSales',
      'monthPurchases',
      'monthProfit',
      'branchSales',
      'topBranches',
      'lowStock'
    ));
  }
}
