<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Laba Rugi</title>
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

    .total-row {
      font-weight: bold;
      background: #f9f9f9;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>Laporan Laba Rugi</h1>
    <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
  </div>

  <table style="width: 100%; margin-bottom: 20px;">
    <tr>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Total Penjualan:</strong> Rp {{ number_format($totalSales, 0, ',', '.') }}
      </td>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Total Pembelian:</strong> Rp {{ number_format($totalPurchases, 0, ',', '.') }}
      </td>
    </tr>
  </table>

  <h3>Ringkasan Laba Rugi</h3>
  <table>
    <tr>
      <td>Total Penjualan</td>
      <td class="text-end">Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td>HPP (Harga Pokok Penjualan)</td>
      <td class="text-end">Rp {{ number_format($hpp, 0, ',', '.') }}</td>
    </tr>
    <tr class="total-row">
      <td>Laba Kotor</td>
      <td class="text-end">Rp {{ number_format($grossProfit, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td>Total Pembelian (Pengeluaran)</td>
      <td class="text-end">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</td>
    </tr>
    <tr class="total-row">
      <td>Laba Bersih</td>
      <td class="text-end">Rp {{ number_format($netProfit, 0, ',', '.') }}</td>
    </tr>
  </table>

  <h3 style="margin-top: 30px;">Detail Harian</h3>
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th class="text-end">Pendapatan</th>
        <th class="text-end">HPP</th>
        <th class="text-end">Laba</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($dailyProfit as $day)
        <tr>
          <td>{{ $day->date }}</td>
          <td class="text-end">Rp {{ number_format($day->revenue, 0, ',', '.') }}</td>
          <td class="text-end">Rp {{ number_format($day->hpp, 0, ',', '.') }}</td>
          <td class="text-end">Rp {{ number_format($day->revenue - $day->hpp, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
