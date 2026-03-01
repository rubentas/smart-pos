@extends('layouts.cashier')

@section('content')
  <h2 class="text-2xl font-bold mb-6">Dashboard Kasir</h2>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Bagian Input Produk -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold text-lg mb-4">Input Produk</h3>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Scan Barcode / Cari Produk</label>
          <input type="text" placeholder="Scan barcode..." class="w-full border rounded-lg px-4 py-2">
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Jumlah</label>
          <input type="number" value="1" min="1" class="w-full border rounded-lg px-4 py-2">
        </div>

        <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
          Tambah ke Keranjang
        </button>
      </div>
    </div>

    <!-- Bagian Keranjang -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold text-lg mb-4">Keranjang Belanja</h3>

        <table class="w-full mb-4">
          <thead>
            <tr class="border-b">
              <th class="text-left py-2">Produk</th>
              <th class="text-center py-2">Qty</th>
              <th class="text-right py-2">Harga</th>
              <th class="text-right py-2">Subtotal</th>
              <th class="text-center py-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b">
              <td colspan="5" class="text-center py-8 text-gray-500">
                Belum ada produk di keranjang
              </td>
            </tr>
          </tbody>
        </table>

        <div class="border-t pt-4">
          <div class="flex justify-between mb-2">
            <span class="font-medium">Total:</span>
            <span class="text-xl font-bold">Rp 0</span>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-4">
            <button class="bg-green-500 text-white py-3 rounded-lg hover:bg-green-600">
              Bayar Tunai
            </button>
            <button class="bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600">
              Bayar Transfer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection
