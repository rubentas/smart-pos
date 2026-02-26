<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pembelian - Smart POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between">
      <h1 class="text-xl font-bold">Smart POS - Detail Pembelian</h1>
      <div>
        <span class="mr-4">{{ Auth::user()->name }} ({{ Auth::user()->role->name }})</span>
        <a href="{{ route('admin.dashboard') }}" class="mr-4 text-blue-500">Dashboard</a>
        <a href="{{ route('admin.purchases.index') }}" class="mr-4 text-blue-500">Pembelian</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="text-red-500">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Detail Pembelian: {{ $purchase->invoice_no }}</h2>
        <a href="{{ route('admin.purchases.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">
          Kembali
        </a>
      </div>

      <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
          <table class="w-full">
            <tr>
              <td class="py-2 text-gray-600">Invoice</td>
              <td class="py-2 font-bold">{{ $purchase->invoice_no }}</td>
            </tr>
            <tr>
              <td class="py-2 text-gray-600">Tanggal</td>
              <td class="py-2">{{ $purchase->date->format('d/m/Y') }}</td>
            </tr>
            <tr>
              <td class="py-2 text-gray-600">Supplier</td>
              <td class="py-2">{{ $purchase->supplier->name }}</td>
            </tr>
          </table>
        </div>
        <div>
          <table class="w-full">
            <tr>
              <td class="py-2 text-gray-600">Petugas</td>
              <td class="py-2">{{ $purchase->user->name }}</td>
            </tr>
            <tr>
              <td class="py-2 text-gray-600">Total</td>
              <td class="py-2 font-bold text-lg">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td class="py-2 text-gray-600">Catatan</td>
              <td class="py-2">{{ $purchase->notes ?? '-' }}</td>
            </tr>
          </table>
        </div>
      </div>

      <h3 class="text-lg font-bold mb-4">Detail Produk</h3>

      <table class="w-full mb-4">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left">Produk</th>
            <th class="px-4 py-2 text-left">Harga</th>
            <th class="px-4 py-2 text-left">Jumlah</th>
            <th class="px-4 py-2 text-left">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($purchase->details as $detail)
            <tr class="border-b">
              <td class="px-4 py-2">{{ $detail->product->name }}</td>
              <td class="px-4 py-2">Rp {{ number_format($detail->purchase_price, 0, ',', '.') }}</td>
              <td class="px-4 py-2">{{ $detail->quantity }}</td>
              <td class="px-4 py-2">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="bg-gray-100">
            <td colspan="3" class="px-4 py-2 text-right font-bold">TOTAL:</td>
            <td class="px-4 py-2 font-bold">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</body>

</html>
