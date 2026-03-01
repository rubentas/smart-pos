@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, ' . Auth::user()->name)

@section('content')
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="stat-card bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
          <i class="fas fa-users text-xl"></i>
        </div>
        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
          <i class="fas fa-arrow-up mr-1 text-xs"></i> 12%
        </span>
      </div>
      <p class="text-slate-500 text-sm font-medium mb-1">Total Users</p>
      <p class="text-3xl font-bold text-slate-800">{{ \App\Models\User::count() }}</p>
    </div>

    <!-- Total Products -->
    <div class="stat-card bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
          <i class="fas fa-box text-xl"></i>
        </div>
        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
          <i class="fas fa-arrow-up mr-1 text-xs"></i> 5%
        </span>
      </div>
      <p class="text-slate-500 text-sm font-medium mb-1">Total Produk</p>
      <p class="text-3xl font-bold text-slate-800">{{ \App\Models\Product::count() }}</p>
    </div>

    <!-- Total Suppliers -->
    <div class="stat-card bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
          <i class="fas fa-truck text-xl"></i>
        </div>
        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">
          <i class="fas fa-arrow-up mr-1 text-xs"></i> 8%
        </span>
      </div>
      <p class="text-slate-500 text-sm font-medium mb-1">Total Supplier</p>
      <p class="text-3xl font-bold text-slate-800">{{ \App\Models\Supplier::count() }}</p>
    </div>

    <!-- Today Sales -->
    <div class="stat-card bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
          <i class="fas fa-chart-line text-xl"></i>
        </div>
        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">
          <i class="fas fa-arrow-down mr-1 text-xs"></i> 2%
        </span>
      </div>
      <p class="text-slate-500 text-sm font-medium mb-1">Penjualan Hari Ini</p>
      <p class="text-3xl font-bold text-slate-800">
        Rp {{ number_format(\App\Models\Sale::whereDate('created_at', today())->sum('total') ?? 0, 0, ',', '.') }}
      </p>
    </div>
  </div>

  <!-- Charts & Alerts -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Chart Section -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h3 class="text-lg font-bold text-slate-800">Analisis Penjualan</h3>
          <p class="text-sm text-slate-500">7 hari terakhir</p>
        </div>
      </div>
      <div class="relative h-80 w-full">
        <canvas id="salesChart"></canvas>
      </div>
    </div>

    <!-- Stock Alert -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-amber-600 text-sm"></i>
          </div>
          <h3 class="text-lg font-bold text-slate-800">Stok Menipis</h3>
        </div>
        <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
          {{ \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count() }} items
        </span>
      </div>

      <div class="space-y-3 max-h-80 overflow-y-auto">
        @php $lowStock = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->take(5)->get(); @endphp
        @forelse($lowStock as $product)
          <div
            class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-xl border border-red-100">
            <div>
              <p class="font-semibold text-slate-800 text-sm mb-1">{{ $product->name }}</p>
              <div class="flex items-center space-x-2 text-xs">
                <span class="text-red-600 font-medium">Stok: {{ $product->stock }}</span>
                <span class="text-slate-400">|</span>
                <span class="text-slate-500">Min: {{ $product->min_stock }}</span>
              </div>
            </div>
            <span
              class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-sm shadow-red-500/30">!</span>
          </div>
        @empty
          <div class="text-center py-8">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
              <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <p class="text-slate-500 font-medium">Semua stok aman</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Recent Purchases -->
  <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
      <div>
        <h3 class="text-lg font-bold text-slate-800">Pembelian Terbaru</h3>
        <p class="text-sm text-slate-500">Transaksi 5 terakhir</p>
      </div>
      <a href="{{ route('admin.purchases.index') }}"
        class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center px-4 py-2 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
        Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-50/50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Invoice</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Supplier</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Total</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @foreach (\App\Models\Purchase::with('supplier')->latest()->take(5)->get() as $purchase)
            <tr class="hover:bg-slate-50/50 transition">
              <td class="px-6 py-4">
                <span
                  class="font-mono font-medium text-slate-800 bg-slate-100 px-3 py-1 rounded-lg text-sm">#{{ $purchase->invoice_no }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-xs font-bold text-blue-600">{{ substr($purchase->supplier->name, 0, 1) }}</span>
                  </div>
                  <span class="font-medium text-slate-800">{{ $purchase->supplier->name }}</span>
                </div>
              </td>
              <td class="px-6 py-4 font-semibold text-slate-800">Rp {{ number_format($purchase->total, 0, ',', '.') }}
              </td>
              <td class="px-6 py-4 text-slate-500 text-sm">
                <i class="far fa-calendar-alt mr-2"></i>
                {{ $purchase->date->format('d M Y') }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    new Chart(document.getElementById('salesChart'), {
      type: 'line',
      data: {
        labels: @json($labels),
        datasets: [{
          label: 'Penjualan',
          data: @json($values),
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          borderWidth: 3,
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>
@endpush
