@extends('admin')

@section('title', 'Laporan Laba Rugi')

@section('content')
  <div class="container-fluid">
    <!-- Filter -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.profit-loss') }}" class="row g-3">
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
            <a href="{{ route('admin.reports.profit-loss', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'pdf']) }}"
              class="btn btn-danger" target="_blank">
              <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <h6 class="text-white-50">Total Penjualan</h6>
            <h4>Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-info text-white">
          <div class="card-body">
            <h6 class="text-white-50">Total Pembelian</h6>
            <h4>Rp {{ number_format($totalPurchases, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-success text-white">
          <div class="card-body">
            <h6 class="text-white-50">Laba Kotor</h6>
            <h4>Rp {{ number_format($grossProfit, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning text-white">
          <div class="card-body">
            <h6 class="text-white-50">Laba Bersih</h6>
            <h4>Rp {{ number_format($netProfit, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Grafik Laba Rugi Harian</h6>
      </div>
      <div class="card-body">
        <canvas id="profitChart" height="100"></canvas>
      </div>
    </div>

    <!-- Detail -->
    <div class="row">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold">Ringkasan Laba Rugi</h6>
          </div>
          <div class="card-body">
            <table class="table table-bordered">
              <tr>
                <td>Total Penjualan</td>
                <td class="text-end fw-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
              </tr>
              <tr>
                <td>HPP (Harga Pokok Penjualan)</td>
                <td class="text-end text-danger">- Rp {{ number_format($hpp, 0, ',', '.') }}</td>
              </tr>
              <tr class="table-primary">
                <td><strong>Laba Kotor</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($grossProfit, 0, ',', '.') }}</strong></td>
              </tr>
              <tr>
                <td>Total Pembelian</td>
                <td class="text-end text-danger">- Rp {{ number_format($totalPurchases, 0, ',', '.') }}</td>
              </tr>
              <tr class="table-success">
                <td><strong>Laba Bersih</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($netProfit, 0, ',', '.') }}</strong></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold">Perhitungan HPP</h6>
          </div>
          <div class="card-body">
            <p class="text-muted mb-3">HPP = Total Pembelian + Stok Awal - Stok Akhir</p>
            <table class="table table-sm">
              <tr>
                <td>Total Pembelian Periode</td>
                <td class="text-end">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</td>
              </tr>
              <tr>
                <td>Nilai Stop Awal</td>
                <td class="text-end">-</td>
              </tr>
              <tr>
                <td>Nilai Stok Akhir</td>
                <td class="text-end">-</td>
              </tr>
              <tr class="fw-bold">
                <td>HPP</td>
                <td class="text-end text-danger">Rp {{ number_format($hpp, 0, ',', '.') }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      new Chart(document.getElementById('profitChart'), {
        type: 'bar',
        data: {
          labels: [
            @foreach ($dailyProfit as $p)
              '{{ $p->date }}',
            @endforeach
          ],
          datasets: [{
              label: 'Pendapatan',
              data: [
                @foreach ($dailyProfit as $p)
                  {{ $p->revenue }},
                @endforeach
              ],
              backgroundColor: '#3b82f6'
            },
            {
              label: 'HPP',
              data: [
                @foreach ($dailyProfit as $p)
                  {{ $p->hpp }},
                @endforeach
              ],
              backgroundColor: '#ef4444'
            }
          ]
        },
        options: {
          responsive: true
        }
      });
    </script>
  @endpush
@endsection
