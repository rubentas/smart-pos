@extends('layouts.admin')

@section('title', 'Detail Retur - ' . $return->return_no)

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">
            <i class="fas fa-undo-alt me-2 text-primary"></i>
            Detail Retur - {{ $return->return_no }}
          </h5>
          <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      <div class="card-body">
        <!-- Info Retur -->
        <div class="row mb-4">
          <div class="col-md-6">
            <table class="table table-sm table-borderless">
              <tr>
                <td width="120">No. Retur</td>
                <td>: <strong>{{ $return->return_no }}</strong></td>
              </tr>
              <tr>
                <td>Tanggal Retur</td>
                <td>: {{ $return->date->format('d/m/Y H:i') }}</td>
              </tr>
              <tr>
                <td>No. Invoice</td>
                <td>:
                  <a href="{{ route('admin.sales.show', $return->sale_id) }}" class="text-primary">
                    {{ $return->sale->invoice_no }}
                  </a>
                </td>
              </tr>
              <tr>
                <td>Kasir</td>
                <td>: {{ $return->user->name }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-sm table-borderless">
              <tr>
                <td width="120">Total Refund</td>
                <td>: <strong class="text-danger">Rp {{ number_format($return->total_refund, 0, ',', '.') }}</strong></td>
              </tr>
              <tr>
                <td>Alasan</td>
                <td>: {{ $return->reason }}</td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Detail Produk -->
        <h6 class="fw-bold mb-3">Detail Produk Directur</h6>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Produk</th>
                <th width="100">Quantity</th>
                <th width="150">Harga</th>
                <th width="150">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($return->details as $detail)
                <tr>
                  <td>
                    <strong>{{ $detail->product->name }}</strong>
                  </td>
                  <td>{{ $detail->quantity }}</td>
                  <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                  <td class="text-danger fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr class="table-light">
                <td colspan="3" class="text-end fw-bold">Total Refund:</td>
                <td class="fw-bold text-danger">Rp {{ number_format($return->total_refund, 0, ',', '.') }}</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
