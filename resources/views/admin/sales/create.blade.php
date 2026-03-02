@extends('layouts.admin')

@section('title', 'POS - Point of Sale')

@section('styles')
  <!-- QuaggaJS untuk barcode scanner -->
  <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
  <style>
    .pos-container {
      display: grid;
      grid-template-columns: 1fr 400px;
      gap: 20px;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 10px;
      max-height: 600px;
      overflow-y: auto;
      padding: 10px;
    }

    .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 10px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .product-card:hover {
      border-color: #007bff;
      box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
      transform: translateY(-2px);
    }

    .product-card .name {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .product-card .price {
      color: #28a745;
      font-weight: bold;
    }

    .product-card .stock {
      font-size: 12px;
      color: #6c757d;
    }

    .product-card .stock.low {
      color: #dc3545;
    }

    .cart-container {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      position: sticky;
      top: 20px;
      max-height: calc(100vh - 100px);
      overflow-y: auto;
    }

    .cart-item {
      display: grid;
      grid-template-columns: 2fr 80px 100px 30px;
      gap: 5px;
      align-items: center;
      margin-bottom: 10px;
      padding: 8px;
      background: white;
      border-radius: 5px;
    }

    .cart-item .item-name {
      font-size: 14px;
      font-weight: 500;
    }

    .cart-item .item-qty input {
      width: 70px;
      text-align: center;
    }

    .cart-item .item-price {
      text-align: right;
      font-weight: bold;
      color: #28a745;
    }

    .cart-item .item-remove {
      color: #dc3545;
      cursor: pointer;
      text-align: center;
    }

    .cart-total {
      border-top: 2px solid #dee2e6;
      margin-top: 15px;
      padding-top: 15px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 16px;
    }

    .grand-total {
      font-size: 24px;
      font-weight: bold;
      color: #007bff;
    }

    .search-box {
      margin-bottom: 15px;
      display: flex;
      gap: 10px;
    }
  </style>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="pos-container">
      <!-- Left Side: Product Grid -->
      <div class="products-section">
        <div class="card">
          <div class="card-header">
            <h5>Pilih Produk</h5>
          </div>
          <div class="card-body">
            <!-- Search Bar -->
            <div class="search-box">
              <input type="text" id="searchProduct" class="form-control" placeholder="Cari nama produk...">
              <button type="button" id="scanBarcodeBtn" class="btn btn-primary">
                <i class="fas fa-camera"></i> Scan
              </button>
            </div>

            <!-- Product Grid -->
            <div class="product-grid" id="productGrid">
              @foreach ($products as $product)
                <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                  data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}">
                  <div class="name">{{ $product->name }}</div>
                  <div class="price">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                  <div class="stock {{ $product->stock <= 5 ? 'low' : '' }}">
                    Stok: {{ $product->stock }}
                  </div>
                  @if ($product->category)
                    <small class="text-muted">{{ $product->category->name }}</small>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Cart -->
      <div class="cart-section">
        <div class="cart-container">
          <h5 class="mb-3">Keranjang Belanja</h5>

          <!-- Cart Items -->
          <div id="cartItems" class="cart-items">
            <!-- Cart items will be inserted here by JavaScript -->
          </div>

          <!-- Discount & Tax -->
          <div class="cart-total">
            <div class="total-row">
              <span>Subtotal:</span>
              <span id="subtotal">Rp 0</span>
            </div>

            <div class="total-row">
              <span>Diskon:</span>
              <div style="display: flex; gap: 5px;">
                <input type="number" id="discount" class="form-control form-control-sm" style="width: 100px;"
                  value="0" min="0">
                <button type="button" id="applyDiscount" class="btn btn-sm btn-primary">Terapkan</button>
              </div>
            </div>

            <div class="total-row">
              <span>PPN 11%:</span>
              <span id="tax">Rp 0</span>
            </div>

            <div class="total-row grand-total">
              <span>Total:</span>
              <span id="total">Rp 0</span>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="form-group mt-3">
            <label>Metode Pembayaran:</label>
            <select id="paymentMethod" class="form-control">
              <option value="cash">Cash</option>
              <option value="transfer">Transfer</option>
              <option value="qris">QRIS</option>
            </select>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex gap-2">
            <button type="button" id="processPayment" class="btn btn-success btn-lg btn-block">
              <i class="fas fa-shopping-cart"></i> Proses Pembayaran
            </button>
            <button type="button" id="clearCart" class="btn btn-danger">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Scanner Barcode -->
  <div class="modal fade" id="scannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Scan Barcode Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="scanner-container" style="width: 100%; height: 300px;"></div>
          <div id="scanner-result" class="mt-3"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    // Cart array
    let cart = [];

    // DOM Elements
    const cartItemsDiv = document.getElementById('cartItems');
    const subtotalSpan = document.getElementById('subtotal');
    const discountInput = document.getElementById('discount');
    const taxSpan = document.getElementById('tax');
    const totalSpan = document.getElementById('total');
    const searchInput = document.getElementById('searchProduct');

    // Filter products by search
    searchInput.addEventListener('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      const productCards = document.querySelectorAll('.product-card');

      productCards.forEach(card => {
        const productName = card.dataset.name.toLowerCase();
        if (productName.includes(searchTerm)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });

    // Add product to cart when clicked
    document.querySelectorAll('.product-card').forEach(card => {
      card.addEventListener('click', function() {
        const productId = parseInt(this.dataset.id);
        const productName = this.dataset.name;
        const productPrice = parseFloat(this.dataset.price);
        const stock = parseInt(this.dataset.stock);

        addToCart(productId, productName, productPrice, stock);
      });
    });

    // Function to add item to cart
    function addToCart(id, name, price, stock) {
      const existingItem = cart.find(item => item.product_id === id);

      if (existingItem) {
        if (existingItem.quantity + 1 > stock) {
          alert('Stok tidak mencukupi!');
          return;
        }
        existingItem.quantity += 1;
        existingItem.subtotal = existingItem.quantity * existingItem.price;
      } else {
        if (1 > stock) {
          alert('Stok tidak mencukupi!');
          return;
        }
        cart.push({
          product_id: id,
          name: name,
          price: price,
          quantity: 1,
          subtotal: price,
          stock: stock
        });
      }

      renderCart();
    }

    function removeFromCart(index) {
      cart.splice(index, 1);
      renderCart();
    }

    function updateQuantity(index, newQty) {
      const item = cart[index];
      newQty = parseInt(newQty);

      if (newQty < 1) {
        removeFromCart(index);
        return;
      }

      if (newQty > item.stock) {
        alert('Stok tidak mencukupi! Maksimal ' + item.stock);
        return;
      }

      item.quantity = newQty;
      item.subtotal = item.quantity * item.price;
      renderCart();
    }

    // Function to calculate totals
    function calculateTotals() {
      const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
      const discount = parseFloat(discountInput.value) || 0;
      const afterDiscount = subtotal - discount;
      const tax = afterDiscount * 0.11; // 11% PPN
      const total = afterDiscount + tax;

      return {
        subtotal,
        discount,
        tax,
        total
      };
    }

    function renderCart() {
      if (cart.length === 0) {
        cartItemsDiv.innerHTML = '<p class="text-muted text-center">Keranjang kosong</p>';
        updateTotals();
        return;
      }

      let html = '';
      cart.forEach((item, index) => {
        html += `
            <div class="cart-item">
                <div class="item-name">${item.name}</div>
                <div class="item-qty">
                    <input type="number" class="form-control form-control-sm" 
                           value="${item.quantity}" min="1" max="${item.stock}"
                           onchange="updateQuantity(${index}, this.value)">
                </div>
                <div class="item-price">Rp ${formatNumber(item.subtotal)}</div>
                <div class="item-remove" onclick="removeFromCart(${index})">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        `;
      });

      cartItemsDiv.innerHTML = html;
      updateTotals();
    }

    function updateTotals() {
      const {
        subtotal,
        discount,
        tax,
        total
      } = calculateTotals();

      subtotalSpan.textContent = 'Rp ' + formatNumber(subtotal);
      taxSpan.textContent = 'Rp ' + formatNumber(tax);
      totalSpan.textContent = 'Rp ' + formatNumber(total);
    }

    // Function to format number
    function formatNumber(num) {
      return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Apply discount
    document.getElementById('applyDiscount').addEventListener('click', function() {
      updateTotals();
    });

    // Clear cart
    document.getElementById('clearCart').addEventListener('click', function() {
      if (confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = [];
        renderCart();
      }
    });

    // Process payment
    document.getElementById('processPayment').addEventListener('click', function() {
      if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
      }

      const {
        subtotal,
        discount,
        tax,
        total
      } = calculateTotals();
      const paymentMethod = document.getElementById('paymentMethod').value;

      const data = {
        cart: cart,
        discount: discount,
        payment_method: paymentMethod,
        _token: '{{ csrf_token() }}'
      };

      const btn = this;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

      fetch('{{ route('sales.store') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Transaksi berhasil!');
            window.open('{{ url('admin/sales') }}/' + data.sale_id + '/invoice', '_blank');
            cart = [];
            window.location.reload();
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          alert('Terjadi kesalahan: ' + error);
        })
        .finally(() => {
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Proses Pembayaran';
        });
    });

    // ============= BARCODE SCANNER =============
    document.getElementById('scanBarcodeBtn').addEventListener('click', function() {
      $('#scannerModal').modal('show');

      setTimeout(function() {
        Quagga.init({
          inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner-container'),
            constraints: {
              width: 640,
              height: 320,
              facingMode: "environment"
            },
          },
          decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
          }
        }, function(err) {
          if (err) {
            console.log(err);
            document.getElementById('scanner-result').innerHTML =
              '<div class="alert alert-danger">Gagal mengakses kamera: ' + err + '</div>';
            return;
          }
          Quagga.start();
        });

        Quagga.onDetected(function(data) {
          let code = data.codeResult.code;
          document.getElementById('scanner-result').innerHTML =
            '<div class="alert alert-success">Barcode terdeteksi: ' + code + '</div>';

          // Cari produk berdasarkan barcode
          cariProdukByBarcode(code);

          setTimeout(function() {
            Quagga.stop();
            $('#scannerModal').modal('hide');
          }, 1500);
        });
      }, 500);
    });

    // Fungsi cari produk via AJAX
    function cariProdukByBarcode(barcode) {
      fetch('/admin/products/search-by-barcode?barcode=' + barcode)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            addToCart(data.product.id, data.product.name, data.product.selling_price, data.product.stock);
          } else {
            alert('Produk dengan barcode ' + barcode + ' tidak ditemukan!');
          }
        });
    }
  </script>
@endsection
