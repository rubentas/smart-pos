@extends('layouts.admin')

@section('title', 'Daftar Penjualan')

@section('content')
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title">Daftar Transaksi Penjualan</h3>
          <a href="{{ route('sales.create') }}" class="btn btn-primary">
            <i class="fas fa-cash-register"></i> POS Baru
          </a>
        </div>
      </div>
      <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('sales.index') }}" class="mb-4">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Invoice</label>
                <input type="text" name="invoice" class="form-control" placeholder="INV-..."
                  value="{{ request('invoice') }}">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control">
                  <option value="">All</option>
                  <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                  <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                  </option>
                  <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">
                  <i class="fas fa-search"></i> Filter
                </button>
              </div>
            </div>
          </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($sales as $sale)
                <tr>
                  <td>
                    <span class="font-weight-bold">{{ $sale->invoice_no }}</span>
                  </td>
                  <td>{{ $sale->date->format('d/m/Y H:i') }}</td>
                  <td>{{ $sale->user->name }}</td>
                  <td class="text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                  <td>
                    @if ($sale->payment_method == 'cash')
                      <span class="badge badge-success">Cash</span>
                    @elseif($sale->payment_method == 'transfer')
                      <span class="badge badge-info">Transfer</span>
                    @else
                      <span class="badge badge-primary">QRIS</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('sales.invoice', $sale->id) }}" class="btn btn-sm btn-warning" target="_blank">
                      <i class="fas fa-print"></i>
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end">
          {{ $sales->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
