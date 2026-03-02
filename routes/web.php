<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

// Rute untuk dashboard (redirect berdasarkan role)
Route::get('/dashboard', function () {
  $user = auth()->user();

  if ($user->role->name === 'Admin') {
    return redirect()->route('admin.dashboard');
  } elseif ($user->role->name === 'Owner') {
    return redirect()->route('owner.dashboard');
  }
  return redirect()->route('cashier.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk profile
Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
  // Dashboard
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  // Users
  Route::resource('users', AdminUserController::class);

  // Categories
  Route::resource('categories', CategoryController::class);

  // Products
  Route::resource('products', ProductController::class);

  // Products search by barcode (UNTUK SCANNER)
  Route::get('/products/search-by-barcode', [ProductController::class, 'searchByBarcode'])->name('products.search-barcode');

  // Suppliers
  Route::resource('suppliers', SupplierController::class);

  // Purchases
  Route::resource('purchases', PurchaseController::class);

  // SALES
  Route::resource('sales', SaleController::class);
  Route::get('sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');

  // Returns
  Route::resource('returns', ReturnController::class);
  Route::get('returns/create-from-sale/{saleId}', [ReturnController::class, 'createFromSale'])->name('returns.create-from-sale');

  // Reports
  Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
    Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
    Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
  });

  // BRANCHES
  Route::resource('branches', BranchController::class);
});

// Rute untuk kasir
Route::middleware(['auth', 'role:Kasir'])->prefix('cashier')->name('cashier.')->group(function () {
  Route::get('/dashboard', function () {
    return view('cashier');
  })->name('dashboard');

  // Kasir juga bisa akses POS
  Route::get('/pos', [SaleController::class, 'create'])->name('pos');
  Route::post('/pos', [SaleController::class, 'store'])->name('pos.store');
});

// Rute untuk OWNER
Route::middleware(['auth', 'role:Owner'])->prefix('owner')->name('owner.')->group(function () {
  Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
