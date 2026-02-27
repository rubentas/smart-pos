<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Pembelian</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th {
      background: #f4f4f4;
      padding: 10px;
      text-align: left;
    }

    td {
      padding: 8px;
      border-bottom: 1px solid #ddd;
    }

    .text-end {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>Laporan Pembelian</h1>
    <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
  </div>

  <table style="width: 100%; margin-bottom: 20px;">
    <tr>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Total Transaksi:</strong> {{ $totalPurchases }}
      </td>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Total Pengeluaran:</strong> Rp {{ number_format($totalSpent, 0, ',', '.') }}
      </td>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Total Item:</strong> {{ $totalItems }}
      </td>
    </tr>
  </table>

  <h3>Detail Transaksi</h3>
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Invoice</th>
        <th>Supplier</th>
        <th class="text-center">Item</th>
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
          <td class="text-end">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
