@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
  <div class="container-fluid">
    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
              <i class="fas fa-search me-1"></i> Tampilkan
            </button>
            <a href="{{ route('admin.reports.sales', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'pdf']) }}"
              class="btn btn-danger me-2" target="_blank">
              <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
            <a href="{{ route('admin.reports.sales', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'excel']) }}"
              class="btn btn-success">
              <i class="fas fa-file-excel me-1"></i> Excel
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Total Transaksi</h6>
            <h3 class="mb-0">{{ $totalSales }}</h3>
            <small>{{ $startDate }} s/d {{ $endDate }}</small>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Total Pendapatan</h6>
            <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Total Item Terjual</h6>
            <h3 class="mb-0">{{ $totalItems }}</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Grafik Penjualan Harian</h6>
      </div>
      <div class="card-body">
        <canvas id="salesChart" height="100"></canvas>
      </div>
    </div>

    <!-- Payment Methods & Top Products -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold">Metode Pembayaran</h6>
          </div>
          <div class="card-body">
            <canvas id="paymentChart" height="200"></canvas>
            <div class="mt-3">
              @foreach ($paymentMethods as $method)
                <div class="d-flex justify-content-between mb-2">
                  <span>
                    <span
                      class="badge bg-{{ $method->payment_method == 'cash' ? 'success' : ($method->payment_method == 'transfer' ? 'info' : 'primary') }} me-2">
                      {{ strtoupper($method->payment_method) }}
                    </span>
                  </span>
                  <span class="fw-bold">Rp {{ number_format($method->total, 0, ',', '.') }}</span>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold">Top 10 Produk Terlaris</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Produk</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-end">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($topProducts as $product)
                    <tr>
                      <td>{{ $product->name }}</td>
                      <td class="text-center">{{ $product->total_qty }}</td>
                      <td class="text-end">Rp {{ number_format($product->total_sales, 0, ',', '.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Detail Transaksi</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Item</th>
                <th>Total</th>
                <th>Metode</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($sales as $sale)
                <tr>
                  <td>{{ $sale->date->format('d/m/Y H:i') }}</td>
                  <td>{{ $sale->invoice_no }}</td>
                  <td>{{ $sale->user->name }}</td>
                  <td>{{ $sale->details->sum('quantity') }}</td>
                  <td class="fw-bold">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                  <td>
                    <span
                      class="badge bg-{{ $sale->payment_method == 'cash' ? 'success' : ($sale->payment_method == 'transfer' ? 'info' : 'primary') }}">
                      {{ strtoupper($sale->payment_method) }}
                    </span>
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
      // Sales Chart
      new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
          labels: [
            @foreach ($dailySales as $sale)
              '{{ $sale->date }}',
            @endforeach
          ],
          datasets: [{
            label: 'Penjualan',
            data: [
              @foreach ($dailySales as $sale)
                {{ $sale->total }},
              @endforeach
            ],
            borderColor: '#3b82f6',
            tension: 0.4,
            fill: false
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

      // Payment Chart
      new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
          labels: [
            @foreach ($paymentMethods as $m)
              '{{ strtoupper($m->payment_method) }}',
            @endforeach
          ],
          datasets: [{
            data: [
              @foreach ($paymentMethods as $m)
                {{ $m->total }},
              @endforeach
            ],
            backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6']
          }]
        }
      });
    </script>
  @endpush
@endsection
