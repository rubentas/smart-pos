<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice {{ $sale->invoice_no }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background: #fff;
      font-size: 14px;
      line-height: 1.5;
      color: #1e293b;
      padding: 40px 20px;
    }

    .invoice {
      max-width: 500px;
      margin: 0 auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px dashed #e2e8f0;
    }

    .header h1 {
      font-size: 28px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .header .store-name {
      font-size: 18px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 4px;
    }

    .header .store-detail {
      color: #64748b;
      font-size: 13px;
    }

    .invoice-info {
      background: #f8fafc;
      padding: 16px;
      border-radius: 12px;
      margin-bottom: 24px;
    }

    .invoice-info table {
      width: 100%;
      border-collapse: collapse;
    }

    .invoice-info td {
      padding: 6px 0;
    }

    .invoice-info .label {
      color: #64748b;
      width: 100px;
    }

    .invoice-info .value {
      font-weight: 600;
      color: #0f172a;
    }

    .invoice-info .invoice-no {
      font-size: 16px;
      letter-spacing: 1px;
    }

    .products {
      margin-bottom: 24px;
    }

    .products table {
      width: 100%;
      border-collapse: collapse;
    }

    .products th {
      text-align: left;
      padding: 10px 0;
      color: #64748b;
      font-weight: 500;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 1px solid #e2e8f0;
    }

    .products td {
      padding: 10px 0;
      border-bottom: 1px solid #f1f5f9;
    }

    .products .product-name {
      font-weight: 500;
      color: #0f172a;
    }

    .products .product-qty {
      color: #64748b;
    }

    .products .text-right {
      text-align: right;
    }

    .summary {
      background: #f8fafc;
      padding: 16px;
      border-radius: 12px;
      margin-bottom: 24px;
    }

    .summary table {
      width: 100%;
      border-collapse: collapse;
    }

    .summary td {
      padding: 6px 0;
    }

    .summary .label {
      color: #64748b;
    }

    .summary .value {
      text-align: right;
      font-weight: 500;
    }

    .summary .total-row {
      border-top: 2px solid #e2e8f0;
      margin-top: 6px;
    }

    .summary .total-row td {
      padding-top: 12px;
      font-weight: 700;
      font-size: 16px;
      color: #0f172a;
    }

    .payment-info {
      text-align: center;
      padding-top: 16px;
      border-top: 2px dashed #e2e8f0;
    }

    .payment-info .method {
      display: inline-block;
      padding: 6px 16px;
      background: #e2e8f0;
      border-radius: 100px;
      font-size: 12px;
      font-weight: 500;
      color: #334155;
      margin-bottom: 8px;
    }

    .payment-info .status {
      color: #059669;
      font-weight: 600;
      font-size: 13px;
    }

    .payment-info .footer-text {
      color: #94a3b8;
      font-size: 11px;
      margin-top: 16px;
    }

    .print-button {
      text-align: center;
      margin-top: 30px;
    }

    .print-button button {
      background: #0f172a;
      color: white;
      border: none;
      padding: 12px 32px;
      border-radius: 100px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s;
    }

    .print-button button:hover {
      background: #1e293b;
    }

    @media print {
      body {
        padding: 0;
        background: white;
      }

      .invoice {
        max-width: 100%;
        box-shadow: none;
        padding: 20px;
      }

      .print-button {
        display: none;
      }

      .header {
        border-bottom: 2px dashed #000;
      }

      .invoice-info {
        background: none;
        border: 1px solid #ddd;
      }

      .summary {
        background: none;
        border: 1px solid #ddd;
      }
    }
  </style>
</head>

<body>
  <div class="invoice">
    <!-- Header -->
    <div class="header">
      <h1>SMART POS</h1>
      <div class="store-name">Toko Smart Anda</div>
      <div class="store-detail">Jl. Contoh No. 123, Jakarta</div>
      <div class="store-detail">Telp: 021-1234567</div>
    </div>

    <!-- Invoice Info -->
    <div class="invoice-info">
      <table>
        <tr>
          <td class="label">No. Invoice</td>
          <td class="value invoice-no">{{ $sale->invoice_no }}</td>
        </tr>
        <tr>
          <td class="label">Tanggal</td>
          <td class="value">{{ $sale->date->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
          <td class="label">Kasir</td>
          <td class="value">{{ $sale->user->name }}</td>
        </tr>
      </table>
    </div>

    <!-- Products -->
    <div class="products">
      <table>
        <thead>
          <tr>
            <th>Produk</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sale->details as $detail)
            <tr>
              <td>
                <div class="product-name">{{ $detail->product->name }}</div>
              </td>
              <td class="text-right product-qty">{{ $detail->quantity }}</td>
              <td class="text-right">Rp {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
              <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="summary">
      <table>
        <tr>
          <td class="label">Subtotal</td>
          <td class="value">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if ($sale->discount > 0)
          <tr>
            <td class="label">Diskon</td>
            <td class="value">- Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
          </tr>
        @endif
        <tr>
          <td class="label">PPN 11%</td>
          <td class="value">Rp {{ number_format($sale->tax, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
          <td class="label">Total</td>
          <td class="value">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
        </tr>
      </table>
    </div>

    <!-- Payment Info -->
    <div class="payment-info">
      <div class="method">
        {{ strtoupper($sale->payment_method) }}
      </div>
      <div class="status">
        ✓ LUNAS
      </div>
      <div class="footer-text">
        Terima kasih atas belanja Anda<br>
        Barang yang sudah dibeli tidak dapat dikembalikan
      </div>
    </div>
  </div>

  <!-- Print Button (only appears on screen, not when printing) -->
  <div class="print-button">
    <button onclick="window.print()">
      <span> Cetak Struk</span>
    </button>
  </div>
</body>

</html>
