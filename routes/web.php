<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
  return view('welcome');
});

// Route untuk dashboard (default Breeze)
Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route untuk Admin - SATU GROUP AJA
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/dashboard', function () {
    return view('admin');
  })->name('dashboard');

  // Route untuk user management
  Route::resource('users', AdminUserController::class);
});

// Route untuk Kasir
Route::middleware(['auth', 'role:Kasir'])->group(function () {
  Route::get('/cashier', function () {
    return view('cashier');
  })->name('cashier.dashboard');
});
