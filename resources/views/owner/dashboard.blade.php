@extends('layouts.owner')

@section('title', 'Owner Dashboard')

@section('content')
  <div class="container-fluid">
    <!-- Welcome Card -->
    <div class="card bg-gradient-primary text-white mb-4">
      <div class="card-body">
        <h4>Welcome back, {{ Auth::user()->name }}!</h4>
        <p class="mb-0">Overview bisnis Anda - {{ now()->format('d F Y') }}</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Total Cabang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBranches }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-store fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Penjualan Hari Ini</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                <small>{{ $todayTransactions }} transaksi</small>
              </div>
              <div class="col-auto">
                <i class="fas fa-cash-register fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  Penjualan Bulan Ini</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($monthSales, 0, ',', '.') }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Laba Bulan Ini</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($monthProfit, 0, ',', '.') }}
                </div>
                <small>Penjualan - Pembelian</small>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan per Cabang (7 Hari)</h6>
          </div>
          <div class="card-body">
            <canvas id="salesChart" height="300"></canvas>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top 5 Cabang</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Cabang</th>
                    <th class="text-end">Penjualan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($topBranches as $branch)
                    <tr>
                      <td>{{ $branch->name }}</td>
                      <td class="text-end">Rp {{ number_format($branch->sales_sum_total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning">Stok Menipis (Semua Cabang)</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Cabang</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Min Stok</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($lowStock as $product)
                <tr>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->branch->name ?? 'Pusat' }}</td>
                  <td class="text-center">{{ $product->stock }}</td>
                  <td class="text-center">{{ $product->min_stock }}</td>
                  <td>
                    <span class="badge bg-warning">Menipis</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      // Data untuk chart (disesuaikan nanti)
      const ctx = document.getElementById('salesChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: {!! json_encode($branchSales->first() ? $branchSales->first()->sales->pluck('date') : []) !!},
          datasets: [
            @foreach ($branchSales as $branch)
              {
                label: '{{ $branch->name }}',
                data: {!! json_encode($branch->sales->pluck('total')) !!},
                borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
                tension: 0.4
              },
            @endforeach
          ]
        }
      });
    </script>
  @endpush
@endsection
