@extends('layouts.admin')

@section('page_title', 'Produk')

@section('content')
  <div class="container mx-auto p-6">
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar Produk</h2>
        <a href="{{ route('admin.products.create') }}"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          + Tambah Produk
        </a>
      </div>

      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barcode</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Beli</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Jual</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Stok</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach ($products as $product)
            <tr>
              <td class="px-6 py-4">{{ $product->barcode }}</td>
              <td class="px-6 py-4">{{ $product->name }}</td>
              <td class="px-6 py-4">{{ $product->category->name }}</td>
              <td class="px-6 py-4">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
              <td class="px-6 py-4">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
              <td class="px-6 py-4">
                <span
                  class="px-2 py-1 text-xs rounded 
                                @if ($product->stock <= $product->min_stock) bg-red-100 text-red-800
                                @else bg-green-100 text-green-800 @endif">
                  {{ $product->stock }}
                </span>
              </td>
              <td class="px-6 py-4">{{ $product->min_stock }}</td>
              <td class="px-6 py-4">
                <a href="{{ route('admin.products.edit', $product) }}"
                  class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
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
        {{ $products->links() }}
      </div>
    </div>
  </div>
@endsection
