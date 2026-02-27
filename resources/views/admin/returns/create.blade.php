@extends('admin')

@section('title', 'Pilih Transaksi untuk Retur')

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
          <i class="fas fa-undo-alt me-2 text-primary"></i>
          Pilih Transaksi untuk Retur
        </h5>
      </div>

      <div class="card-body">
        <!-- Search -->
        <div class="row mb-4">
          <div class="col-md-6">
            <input type="text" id="searchInvoice" class="form-control" placeholder="Cari no invoice...">
          </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="salesTable">
              @forelse($sales as $sale)
                <tr>
                  <td>
                    <span class="fw-bold">{{ $sale->invoice_no }}</span>
                  </td>
                  <td>{{ $sale->date->format('d/m/Y H:i') }}</td>
                  <td>{{ $sale->user->name }}</td>
                  <td class="fw-bold">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                  <td>
                    <a href="{{ route('admin.returns.create-from-sale', $sale->id) }}" class="btn btn-sm btn-primary">
                      <i class="fas fa-undo-alt me-1"></i> Proses Retur
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <i class="fas fa-undo-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada transaksi hari ini</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.getElementById('searchInvoice').addEventListener('keyup', function() {
        let search = this.value.toLowerCase();
        let rows = document.querySelectorAll('#salesTable tr');

        rows.forEach(row => {
          let text = row.cells[0]?.innerText.toLowerCase() || '';
          row.style.display = text.includes(search) ? '' : 'none';
        });
      });
    </script>
  @endpush
@endsection
