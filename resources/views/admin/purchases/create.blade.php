@extends('layouts.admin')

@section('page_title', 'Tambah Pembelian')

@section('content')
  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6">Tambah Pembelian Baru</h2>

      <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        <div class="grid grid-cols-3 gap-4 mb-6">
          <div>
            <label class="block text-sm font-medium mb-2">Invoice</label>
            <input type="text" name="invoice_no" value="PO-{{ date('Ymd') }}-{{ rand(100, 999) }}"
              class="w-full border rounded-lg px-4 py-2 bg-gray-100" readonly>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Tanggal</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border rounded-lg px-4 py-2"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Supplier</label>
            <select name="supplier_id" class="w-full border rounded-lg px-4 py-2" required>
              <option value="">Pilih Supplier</option>
              @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Catatan</label>
          <textarea name="notes" rows="2" class="w-full border rounded-lg px-4 py-2"></textarea>
        </div>

        <h3 class="text-lg font-bold mb-4">Detail Produk</h3>

        <div class="mb-4 flex gap-2">
          <select id="productSelect" class="border rounded-lg px-4 py-2 flex-1">
            <option value="">Pilih Produk</option>
            @foreach ($products as $product)
              <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}"
                data-name="{{ $product->name }}">
                {{ $product->name }} - Rp {{ number_format($product->purchase_price, 0, ',', '.') }} (Stok:
                {{ $product->stock }})
              </option>
            @endforeach
          </select>
          <input type="number" id="quantityInput" placeholder="Jumlah" min="1" value="1"
            class="border rounded-lg px-4 py-2 w-32">
          <button type="button" id="addProductBtn"
            class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
            Tambah
          </button>
        </div>

        <table class="w-full mb-4" id="productsTable">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left">Produk</th>
              <th class="px-4 py-2 text-left">Harga</th>
              <th class="px-4 py-2 text-left">Jumlah</th>
              <th class="px-4 py-2 text-left">Subtotal</th>
              <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody id="productList">
            <!-- Produk akan ditambahkan di sini via JavaScript -->
          </tbody>
          <tfoot>
            <tr class="bg-gray-100">
              <td colspan="3" class="px-4 py-2 text-right font-bold">TOTAL:</td>
              <td class="px-4 py-2 font-bold" id="totalDisplay">Rp 0</td>
              <td></td>
            </tr>
          </tfoot>
        </table>

        <input type="hidden" name="products" id="productsInput">
        <input type="hidden" name="total" id="totalInput">

        <div class="flex justify-end">
          <a href="{{ route('admin.purchases.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg mr-2">
            Batal
          </a>
          <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg">
            Simpan Pembelian
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    let products = [];

    function formatRupiah(angka) {
      return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateTable() {
      let html = '';
      let total = 0;

      products.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;

        html += `<tr>
                    <td class="px-4 py-2">${item.name}</td>
                    <td class="px-4 py-2">${formatRupiah(item.price)}</td>
                    <td class="px-4 py-2">${item.quantity}</td>
                    <td class="px-4 py-2">${formatRupiah(subtotal)}</td>
                    <td class="px-4 py-2">
                        <button type="button" onclick="removeProduct(${index})" 
                                class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                </tr>`;
      });

      document.getElementById('productList').innerHTML = html;
      document.getElementById('totalDisplay').innerText = formatRupiah(total);
      document.getElementById('totalInput').value = total;
      document.getElementById('productsInput').value = JSON.stringify(products);
    }

    function removeProduct(index) {
      products.splice(index, 1);
      updateTable();
    }

    document.getElementById('addProductBtn').addEventListener('click', function() {
      let select = document.getElementById('productSelect');
      let quantity = document.getElementById('quantityInput').value;

      if (!select.value) {
        alert('Pilih produk dulu!');
        return;
      }

      if (quantity < 1) {
        alert('Jumlah minimal 1');
        return;
      }

      let selectedOption = select.options[select.selectedIndex];
      let productId = select.value;
      let productName = selectedOption.dataset.name;
      let productPrice = parseFloat(selectedOption.dataset.price);

      // Cek apakah produk sudah ada di keranjang
      let existingIndex = products.findIndex(p => p.id == productId);

      if (existingIndex >= 0) {
        // Update quantity
        products[existingIndex].quantity += parseInt(quantity);
      } else {
        // Tambah baru
        products.push({
          id: productId,
          name: productName,
          price: productPrice,
          quantity: parseInt(quantity)
        });
      }

      updateTable();

      // Reset
      select.value = '';
      document.getElementById('quantityInput').value = 1;
    });

    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
      if (products.length === 0) {
        e.preventDefault();
        alert('Minimal 1 produk harus ditambahkan!');
      }
    });
  </script>
@endsection
