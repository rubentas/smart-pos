<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index()
  {
    $branchId = session('active_branch');

    // Stats Cards
    $totalProducts = Product::where('branch_id', $branchId)->count();
    $totalUsers = User::count(); // user global
    $totalSuppliers = Supplier::count(); // supplier global
    $todaySales = Sale::where('branch_id', $branchId)
      ->whereDate('created_at', today())
      ->sum('total');

    // Stok menipis
    $lowStock = Product::where('branch_id', $branchId)
      ->whereColumn('stock', '<=', 'min_stock')
      ->take(5)
      ->get();

    // Pembelian terbaru
    $recentPurchases = Purchase::with('supplier')
      ->where('branch_id', $branchId)
      ->latest()
      ->take(5)
      ->get();

    // Data chart 7 hari
    $chartData = Sale::where('branch_id', $branchId)
      ->whereBetween('created_at', [now()->subDays(6), now()])
      ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
      ->groupBy(DB::raw('DATE(created_at)'))
      ->orderBy('date')
      ->get();

    $labels = [];
    $values = [];

    for ($i = 6; $i >= 0; $i--) {
      $date = now()->subDays($i)->format('Y-m-d');
      $labels[] = now()->subDays($i)->format('D');
      $sale = $chartData->firstWhere('date', $date);
      $values[] = $sale ? $sale->total : 0;
    }

    return view('admin.dashboard', compact(
      'totalProducts',
      'totalUsers',
      'totalSuppliers',
      'todaySales',
      'lowStock',
      'recentPurchases',
      'labels',
      'values'
    ));
  }
}