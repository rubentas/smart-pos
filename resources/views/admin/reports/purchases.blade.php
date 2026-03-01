@extends('layouts.admin')

@section('title', 'Laporan Pembelian')

@section('content')
  <div class="container-fluid">
    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.purchases') }}" class="row g-3">
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
            <a href="{{ route('admin.reports.purchases', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'pdf']) }}"
              class="btn btn-danger" target="_blank">
              <i class="fas fa-file-pdf me-1"></i> Export PDF
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
            <h3 class="mb-0">{{ $totalPurchases }}</h3>
            <small>{{ $startDate }} s/d {{ $endDate }}</small>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Total Pengeluaran</h6>
            <h3 class="mb-0">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm">
          <div class="card-body">
            <h6 class="text-white-50">Total Item Dibeli</h6>
            <h3 class="mb-0">{{ $totalItems }}</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Supplier Summary -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Ringkasan per Supplier</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>Supplier</th>
                <th class="text-center">Jumlah Transaksi</th>
                <th class="text-end">Total Pembelian</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($supplierSummary as $summary)
                <tr>
                  <td>{{ $summary->supplier->name }}</td>
                  <td class="text-center">{{ $summary->count }}</td>
                  <td class="text-end fw-bold">Rp {{ number_format($summary->total, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Detail Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Detail Transaksi Pembelian</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Supplier</th>
                <th class="text-center">Item</th>
                <th class="text-end">Subtotal</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($purchases as $purchase)
                <tr>
                  <td>{{ $purchase->date->format('d/m/Y') }}</td>
                  <td>{{ $purchase->invoice_no }}</td>
                  <td>{{ $purchase->supplier->name }}</td>
                  <td class="text-center">{{ $purchase->details->sum('quantity') }}</td>
                  <td class="text-end">Rp {{ number_format($purchase->subtotal, 0, ',', '.') }}</td>
                  <td class="text-end fw-bold">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
