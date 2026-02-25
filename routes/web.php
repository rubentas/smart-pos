<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProfileController; // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
  return view('welcome');
});

// dashboard
Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/dashboard', function () {
    return view('admin');
  })->name('dashboard');

  // User management
  Route::resource('users', AdminUserController::class);
});

// Kasir
Route::middleware(['auth', 'role:Kasir'])->group(function () {
  Route::get('/cashier', function () {
    return view('cashier');
  })->name('cashier.dashboard');
});

// Profile routes
Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
