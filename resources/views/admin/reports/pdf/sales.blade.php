<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Penjualan</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header h1 {
      margin: 0;
      color: #333;
    }

    .header p {
      margin: 5px 0;
      color: #666;
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

    .summary {
      margin-top: 20px;
    }

    .summary-box {
      border: 1px solid #ddd;
      padding: 10px;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>Laporan Penjualan</h1>
    <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
  </div>

  <div class="summary">
    <table style="width: 100%; margin-bottom: 20px;">
      <tr>
        <td style="border: 1px solid #ddd; padding: 10px;">
          <strong>Total Transaksi:</strong> {{ $totalSales }}
        </td>
        <td style="border: 1px solid #ddd; padding: 10px;">
          <strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </td>
        <td style="border: 1px solid #ddd; padding: 10px;">
          <strong>Total Item:</strong> {{ $totalItems }}
        </td>
      </tr>
    </table>
  </div>

  <h3>Detail Transaksi</h3>
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Invoice</th>
        <th>Kasir</th>
        <th class="text-center">Item</th>
        <th class="text-end">Total</th>
        <th>Metode</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $sale)
        <tr>
          <td>{{ $sale->date->format('d/m/Y H:i') }}</td>
          <td>{{ $sale->invoice_no }}</td>
          <td>{{ $sale->user->name }}</td>
          <td class="text-center">{{ $sale->details->sum('quantity') }}</td>
          <td class="text-end">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
          <td>{{ strtoupper($sale->payment_method) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top: 30px;">
    <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
  </div>
</body>

</html>
