@extends('admin')

@section('title', 'Laporan Stok')

@section('content')
  <div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Total Produk</h6>
            <h3 class="mb-0">{{ $totalProducts }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Total Stok</h6>
            <h3 class="mb-0">{{ $totalStock }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Stok Menipis</h6>
            <h3 class="mb-0">{{ $lowStock }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-danger text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Stok Habis</h6>
            <h3 class="mb-0">{{ $outOfStock }}</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form method="GET" class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
              <i class="fas fa-search me-1"></i> Filter Mutasi
            </button>
            <a href="{{ route('admin.reports.stock', ['export' => 'pdf']) }}" class="btn btn-danger" target="_blank">
              <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Stock Table -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Status Stok</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Min Stok</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $product)
                <tr>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->category->name ?? '-' }}</td>
                  <td>{{ $product->supplier->name ?? '-' }}</td>
                  <td class="text-center">{{ $product->stock }}</td>
                  <td class="text-center">{{ $product->min_stock }}</td>
                  <td>
                    @if ($product->stock == 0)
                      <span class="badge bg-danger">Habis</span>
                    @elseif($product->stock <= $product->min_stock)
                      <span class="badge bg-warning">Menipis</span>
                    @else
                      <span class="badge bg-success">Aman</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Stock Logs -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Mutasi Stok</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Tipe</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Stok Lama</th>
                <th class="text-center">Stok Baru</th>
                <th>Referensi</th>
                <th>User</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($stockLogs as $log)
                <tr>
                  <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                  <td>{{ $log->product->name }}</td>
                  <td>
                    @if ($log->type == 'IN')
                      <span class="badge bg-success">IN</span>
                    @elseif($log->type == 'OUT')
                      <span class="badge bg-danger">OUT</span>
                    @else
                      <span class="badge bg-info">RETURN</span>
                    @endif
                  </td>
                  <td class="text-center">{{ $log->quantity }}</td>
                  <td class="text-center">{{ $log->old_stock }}</td>
                  <td class="text-center">{{ $log->new_stock }}</td>
                  <td><small>{{ $log->reference }}</small></td>
                  <td>{{ $log->user->name ?? '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
