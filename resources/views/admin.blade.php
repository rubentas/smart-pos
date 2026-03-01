@extends('layouts.admin')

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stats Cards -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Total Produk</p>
          <p class="text-3xl font-bold text-slate-800">1,234</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
          <i class="fas fa-box text-xl text-blue-600"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Total Penjualan</p>
          <p class="text-3xl font-bold text-slate-800">Rp 50M</p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
          <i class="fas fa-cash-register text-xl text-green-600"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Total Pembelian</p>
          <p class="text-3xl font-bold text-slate-800">Rp 25M</p>
        </div>
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
          <i class="fas fa-shopping-cart text-xl text-purple-600"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-slate-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500">Total Supplier</p>
          <p class="text-3xl font-bold text-slate-800">42</p>
        </div>
        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
          <i class="fas fa-truck text-xl text-orange-600"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Sales -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100">
      <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800">Penjualan Terbaru</h3>
      </div>
      <div class="p-6">
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="font-medium text-slate-800">Transaksi #001</p>
              <p class="text-sm text-slate-500">2 jam lalu</p>
            </div>
            <p class="font-semibold text-slate-800">Rp 150,000</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Purchases -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100">
      <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800">Pembelian Terbaru</h3>
      </div>
      <div class="p-6">
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="font-medium text-slate-800">PO #001</p>
              <p class="text-sm text-slate-500">1 jam lalu</p>
            </div>
            <p class="font-semibold text-slate-800">Rp 5,000,000</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
