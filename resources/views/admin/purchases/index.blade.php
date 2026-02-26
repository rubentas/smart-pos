<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembelian - Smart POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between">
      <h1 class="text-xl font-bold">Smart POS - Pembelian</h1>
      <div>
        <span class="mr-4">{{ Auth::user()->name }} ({{ Auth::user()->role->name }})</span>
        <a href="{{ route('admin.dashboard') }}" class="mr-4 text-blue-500">Dashboard</a>
        <a href="{{ route('admin.categories.index') }}" class="mr-4 text-blue-500">Kategori</a>
        <a href="{{ route('admin.products.index') }}" class="mr-4 text-blue-500">Produk</a>
        <a href="{{ route('admin.suppliers.index') }}" class="mr-4 text-blue-500">Supplier</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="text-red-500">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mx-auto p-6">
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar Pembelian</h2>
        <a href="{{ route('admin.purchases.create') }}"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          + Tambah Pembelian
        </a>
      </div>

      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach ($purchases as $purchase)
            <tr>
              <td class="px-6 py-4">{{ $purchase->invoice_no }}</td>
              <td class="px-6 py-4">{{ $purchase->date->format('d/m/Y') }}</td>
              <td class="px-6 py-4">{{ $purchase->supplier->name }}</td>
              <td class="px-6 py-4">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
              <td class="px-6 py-4">{{ $purchase->user->name }}</td>
              <td class="px-6 py-4">
                <a href="{{ route('admin.purchases.show', $purchase) }}"
                  class="text-green-500 hover:text-green-700 mr-3">Detail</a>
                <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-500 hover:text-red-700"
                    onclick="return confirm('Yakin ingin menghapus?')">
                    Hapus
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="p-6">
        {{ $purchases->links() }}
      </div>
    </div>
  </div>
</body>

</html>
