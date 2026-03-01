@extends('layouts.admin')

@section('title', 'Proses Retur - ' . $sale->invoice_no)

@section('content')
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">
            <i class="fas-undo-alt me-2 text-primary"></i>
            Retur Barang - {{ $sale->invoice_no }}
          </h5>
          <a href="{{ route('admin.returns.create') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      <div class="card-body">
        <!-- Info Transaksi -->
        <div class="bg-light p-4 rounded mb-4">
          <div class="row">
            <div class="col-md-6">
              <table class="table table-sm table-borderless">
                <tr>
                  <td width="120">No. Invoice</td>
                  <td>: <strong>{{ $sale->invoice_no }}</strong></td>
                </tr>
                <tr>
                  <td>Tanggal</td>
                  <td>: {{ $sale->date->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                  <td>Kasir</td>
                  <td>: {{ $sale->user->name }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-sm table-borderless">
                <tr>
                  <td width="120">Total</td>
                  <td>: <strong class="text-primary">Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                  <td>Metode Bayar</td>
                  <td>: <span class="badge bg-info">{{ strtoupper($sale->payment_method) }}</span></td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- Form Retur -->
        <form id="returnForm">
          @csrf
          <input type="hidden" name="sale_id" value="{{ $sale->id }}">

          <div class="table-responsive mb-4">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Produk</th>
                  <th width="100">Terjual</th>
                  <th width="100">Sudah Retur</th>
                  <th width="100">Bisa Retur</th>
                  <th width="120">Harga</th>
                  <th width="150">Qty Retur</th>
                  <th width="150">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($sale->details as $detail)
                  @php
                    $soldQty = $detail->quantity;
                    $returnedQty = $returnedQuantities[$detail->id] ?? 0;
                    $maxReturn = $soldQty - $returnedQty;
                  @endphp
                  @if ($maxReturn > 0)
                    <tr>
                      <td>
                        <strong>{{ $detail->product->name }}</strong>
                        <input type="hidden" name="items[{{ $loop->index }}][sale_detail_id]"
                          value="{{ $detail->id }}">
                        <input type="hidden" name="items[{{ $loop->index }}][product_id]"
                          value="{{ $detail->product_id }}">
                        <input type="hidden" name="items[{{ $loop->index }}][price]"
                          value="{{ $detail->selling_price }}">
                        <input type="hidden" name="items[{{ $loop->index }}][max_qty]" value="{{ $maxReturn }}">
                      </td>
                      <td class="text-center">{{ $soldQty }}</td>
                      <td class="text-center">{{ $returnedQty }}</td>
                      <td class="text-center">{{ $maxReturn }}</td>
                      <td>Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                      <td>
                        <input type="number" class="form-control form-control-sm qty-input"
                          name="items[{{ $loop->index }}][quantity]" value="0" min="0"
                          max="{{ $maxReturn }}" data-price="{{ $detail->selling_price }}"
                          data-index="{{ $loop->index }}">
                      </td>
                      <td>
                        <span class="subtotal" id="subtotal-{{ $loop->index }}">Rp 0</span>
                      </td>
                    </tr>
                  @endif
                @endforeach
              </tbody>
              <tfoot>
                <tr class="table-light">
                  <td colspan="6" class="text-end fw-bold">Total Refund:</td>
                  <td class="fw-bold text-danger" id="totalRefund">Rp 0</td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Alasan Retur <span class="text-danger">*</span></label>
              <textarea name="reason" id="reason" rows="3" class="form-control"
                placeholder="Contoh: Barang rusak, salah ukuran, dll" required></textarea>
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" id="submitBtn" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Proses Retur
            </button>
            <a href="{{ route('admin.returns.create') }}" class="btn btn-secondary">
              <i class="fas fa-times me-1"></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', calculateAll);
      });

      function calculateAll() {
        let totalRefund = 0;

        document.querySelectorAll('.qty-input').forEach(input => {
          let qty = parseInt(input.value) || 0;
          let price = parseFloat(input.dataset.price);
          let subtotal = qty * price;
          let index = input.dataset.index;

          document.getElementById(`subtotal-${index}`).innerText = 'Rp ' + formatNumber(subtotal);
          totalRefund += subtotal;
        });

        document.getElementById('totalRefund').innerText = 'Rp ' + formatNumber(totalRefund);
      }

      function formatNumber(num) {
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      // Submit form
      document.getElementById('returnForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Cek apakah ada item yang diretur
        let hasItems = false;
        document.querySelectorAll('.qty-input').forEach(input => {
          if (parseInt(input.value) > 0) hasItems = true;
        });

        if (!hasItems) {
          alert('Pilih minimal 1 item untuk diretur!');
          return;
        }

        if (!document.getElementById('reason').value.trim()) {
          alert('Alasan retur harus diisi!');
          return;
        }

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';

        fetch('{{ route('admin.returns.store') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              sale_id: '{{ $sale->id }}',
              reason: document.getElementById('reason').value,
              items: Array.from(document.querySelectorAll('.qty-input')).map(input => {
                return {
                  sale_detail_id: input.closest('tr').querySelector('input[name*="[sale_detail_id]"]').value,
                  product_id: input.closest('tr').querySelector('input[name*="[product_id]"]').value,
                  quantity: parseInt(input.value) || 0,
                  price: parseFloat(input.dataset.price),
                  max_qty: parseInt(input.closest('tr').querySelector('input[name*="[max_qty]"]').value)
                };
              }).filter(item => item.quantity > 0)
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Retur berhasil diproses!');
              window.location.href = '{{ route('admin.returns.index') }}';
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            alert('Terjadi kesalahan: ' + error);
          })
          .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i> Proses Retur';
          });
      });
    </script>
  @endpush
@endsection
