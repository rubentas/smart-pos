@extends('admin')

@section('title', 'Laporan')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 mb-4">
        <h4 class="fw-bold">Pilih Jenis Laporan</h4>
      </div>

      <!-- Card Penjualan -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-4">
            <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3"
              style="width: 80px; height: 80px; line-height: 80px;">
              <i class="fas fa-shopping-cart fa-3x text-primary"></i>
            </div>
            <h5 class="fw-bold">Penjualan</h5>
            <p class="text-muted small">Rekap transaksi penjualan per periode</p>
            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#salesModal">
              <i class="fas fa-chart-line me-1"></i> Lihat Laporan
            </a>
          </div>
        </div>
      </div>

      <!-- Card Pembelian -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-4">
            <div class="bg-success bg-opacity-10 rounded-circle mx-auto mb-3"
              style="width: 80px; height: 80px; line-height: 80px;">
              <i class="fas fa-truck fa-3x text-success"></i>
            </div>
            <h5 class="fw-bold">Pembelian</h5>
            <p class="text-muted small">Rekap pembelian dari supplier</p>
            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#purchaseModal">
              <i class="fas fa-chart-line me-1"></i> Lihat Laporan
            </a>
          </div>
        </div>
      </div>

      <!-- Card Stok -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-4">
            <div class="bg-info bg-opacity-10 rounded-circle mx-auto mb-3"
              style="width: 80px; height: 80px; line-height: 80px;">
              <i class="fas fa-boxes fa-3x text-info"></i>
            </div>
            <h5 class="fw-bold">Stok</h5>
            <p class="text-muted small">Informasi stok dan mutasi barang</p>
            <a href="{{ route('admin.reports.stock') }}" class="btn btn-info btn-sm">
              <i class="fas fa-boxes me-1"></i> Lihat Laporan
            </a>
          </div>
        </div>
      </div>

      <!-- Card Laba Rugi -->
      <div class="col-md-6 col-lg-3 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body text-center p-4">
            <div class="bg-warning bg-opacity-10 rounded-circle mx-auto mb-3"
              style="width: 80px; height: 80px; line-height: 80px;">
              <i class="fas fa-chart-pie fa-3x text-warning"></i>
            </div>
            <h5 class="fw-bold">Laba Rugi</h5>
            <p class="text-muted small">Analisis keuntungan dan kerugian</p>
            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#profitModal">
              <i class="fas fa-chart-pie me-1"></i> Lihat Laporan
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Penjualan -->
  <div class="modal fade" id="salesModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Filter Laporan Penjualan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('admin.reports.sales') }}" method="GET">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" class="form-control" required
                value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">End Date</label>
              <input type="date" name="end_date" class="form-control" required value="{{ now()->format('Y-m-d') }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Lihat Laporan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Pembelian -->
  <div class="modal fade" id="purchaseModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Filter Laporan Pembelian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('admin.reports.purchases') }}" method="GET">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" class="form-control" required
                value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">End Date</label>
              <input type="date" name="end_date" class="form-control" required
                value="{{ now()->format('Y-m-d') }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Lihat Laporan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Laba Rugi -->
  <div class="modal fade" id="profitModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Filter Laporan Laba Rugi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('admin.reports.profit-loss') }}" method="GET">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" class="form-control" required
                value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">End Date</label>
              <input type="date" name="end_date" class="form-control" required
                value="{{ now()->format('Y-m-d') }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-warning">Lihat Laporan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
