@extends('layouts.admin')

@section('title', 'Detail Transaksi - ' . $sale->invoice_no)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm">
          <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0 fw-bold">
                <i class="fas fa-receipt me-2 text-primary"></i>
                Detail Transaksi
              </h5>
              <div>
                <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank"
                  class="btn btn-outline-primary btn-sm me-2">
                  <i class="fas fa-print me-1"></i> Cetak Struk
                </a>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm">
                  <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
              </div>
            </div>
          </div>

          <div class="card-body">
            <!-- Info Transaksi -->
            <div class="row mb-4">
              <div class="col-md-6">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td width="120" class="text-muted">No. Invoice</td>
                    <td width="20">:</td>
                    <td class="fw-bold">{{ $sale->invoice_no }}</td>
                  </tr>
                  <tr>
                    <td class="text-muted">Tanggal</td>
                    <td>:</td>
                    <td>{{ $sale->date->format('d/m/Y H:i') }}</td>
                  </tr>
                  <tr>
                    <td class="text-muted">Kasir</td>
                    <td>:</td>
                    <td>{{ $sale->user->name }}</td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td width="120" class="text-muted">Metode Bayar</td>
                    <td width="20">:</td>
                    <td>
                      @if ($sale->payment_method == 'cash')
                        <span class="badge bg-success">Cash</span>
                      @elseif($sale->payment_method == 'transfer')
                        <span class="badge bg-info">Transfer</span>
                      @else
                        <span class="badge bg-primary">QRIS</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">Status</td>
                    <td>:</td>
                    <td><span class="badge bg-success">Lunas</span></td>
                  </tr>
                </table>
              </div>
            </div>

            <!-- Detail Produk -->
            <h6 class="fw-bold mb-3">Detail Produk</h6>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Produk</th>
                    <th class="text-center" width="100">Quantity</th>
                    <th class="text-end" width="150">Harga</th>
                    <th class="text-end" width="150">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($sale->details as $detail)
                    <tr>
                      <td>
                        <div class="fw-bold">{{ $detail->product->name }}</div>
                        @if ($detail->product->category)
                          <small class="text-muted">{{ $detail->product->category->name }}</small>
                        @endif
                      </td>
                      <td class="text-center">{{ $detail->quantity }}</td>
                      <td class="text-end">Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                      <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="row mt-4">
              <div class="col-md-6 offset-md-6">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td class="text-muted">Subtotal</td>
                    <td class="text-end">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                  </tr>
                  @if ($sale->discount > 0)
                    <tr>
                      <td class="text-muted">Diskon</td>
                      <td class="text-end text-danger">- Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                    </tr>
                  @endif
                  <tr>
                    <td class="text-muted">PPN 11%</td>
                    <td class="text-end">Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
                  </tr>
                  <tr class="fw-bold">
                    <td>Total</td>
                    <td class="text-end text-primary fs-5">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
