<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Laporan Stok</title>
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
      padding: 8px;
      text-align: left;
    }

    td {
      padding: 6px;
      border-bottom: 1px solid #ddd;
    }

    .badge {
      padding: 3px 6px;
      border-radius: 3px;
      color: white;
    }

    .bg-danger {
      background: #dc3545;
    }

    .bg-warning {
      background: #ffc107;
      color: #000;
    }

    .bg-success {
      background: #28a745;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>Laporan Stok</h1>
    <p>Tanggal: {{ now()->format('d/m/Y H:i') }}</p>
  </div>

  <table style="width: 100%; margin-bottom: 20px;">
    <tr>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Total Produk:</strong> {{ $totalProducts }}
      </td>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Total Stok:</strong> {{ $totalStock }}
      </td>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Stok Menipis:</strong> {{ $lowStock }}
      </td>
      <td style="border: 1px solid #ddd; padding: 10px;">
        <strong>Stok Habis:</strong> {{ $outOfStock }}
      </td>
    </tr>
  </table>

  <h3>Status Stok</h3>
  <table>
    <thead>
      <tr>
        <th>Produk</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Min Stok</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $product)
        <tr>
          <td>{{ $product->name }}</td>
          <td>{{ $product->category->name ?? '-' }}</td>
          <td>{{ $product->stock }}</td>
          <td>{{ $product->min_stock }}</td>
          <td>
            @if ($product->stock == 0)
              <span class="badge bg-danger">Habis</span>
            @elseif($product->stock <= $product->min_stock)
              <span class="badge bg-warning">Menipis</span>
            @else
              <span class="badge bg-success">Aman</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
