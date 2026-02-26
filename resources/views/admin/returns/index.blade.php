@extends('admin')

@section('title', 'Daftar Retur Barang')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">
            <i class="fas fa-undo-alt me-2 text-primary"></i>
            Daftar Retur Barang
          </h5>
          <a href="{{ route('admin.returns.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Retur Baru
          </a>
        </div>
      </div>

      <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
          <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">No. Retur</label>
            <input type="text" name="return_no" class="form-control" placeholder="RTR-..."
              value="{{ request('return_no') }}">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
              <i class="fas fa-search me-1"></i> Filter
            </button>
          </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>No. Retur</th>
                <th>Tanggal</th>
                <th>No. Invoice</th>
                <th>Kasir</th>
                <th>Total Refund</th>
                <th>Alasan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($returns as $return)
                <tr>
                  <td>
                    <span class="fw-bold">{{ $return->return_no }}</span>
                  </td>
                  <td>{{ $return->date->format('d/m/Y H:i') }}</td>
                  <td>
                    <a href="{{ route('admin.sales.show', $return->sale_id) }}" class="text-primary">
                      {{ $return->sale->invoice_no }}
                    </a>
                  </td>
                  <td>{{ $return->user->name }}</td>
                  <td class="text-danger fw-bold">
                    Rp {{ number_format($return->total_refund, 0, ',', '.') }}
                  </td>
                  <td>
                    <span class="text-truncate d-inline-block" style="max-width: 200px;">
                      {{ $return->reason }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ route('admin.returns.show', $return->id) }}" class="btn btn-sm btn-info">
                      <i class="fas fa-eye"></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4">
                    <i class="fas fa-undo-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data retur</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end">
          {{ $returns->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
