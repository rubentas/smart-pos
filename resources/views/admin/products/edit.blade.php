@extends('layouts.admin')

@section('page_title', 'Edit Produk')

@section('content')
  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6">Edit Produk: {{ $product->name }}</h2>

      <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}"
              class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror" required>
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Barcode</label>
            <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
              class="w-full border rounded-lg px-4 py-2 @error('barcode') border-red-500 @enderror" required>
            @error('barcode')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Kategori</label>
            <select name="category_id"
              class="w-full border rounded-lg px-4 py-2 @error('category_id') border-red-500 @enderror" required>
              <option value="">Pilih Kategori</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                  {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Harga Beli</label>
            <input type="number" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}"
              class="w-full border rounded-lg px-4 py-2 @error('purchase_price') border-red-500 @enderror" required>
            @error('purchase_price')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Harga Jual</label>
            <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}"
              class="w-full border rounded-lg px-4 py-2 @error('selling_price') border-red-500 @enderror" required>
            @error('selling_price')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Stok Awal</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
              class="w-full border rounded-lg px-4 py-2 @error('stock') border-red-500 @enderror" required>
            @error('stock')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Minimum Stok</label>
            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}"
              class="w-full border rounded-lg px-4 py-2 @error('min_stock') border-red-500 @enderror" required>
            @error('min_stock')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="flex justify-end">
          <a href="{{ route('admin.products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg mr-2">
            Batal
          </a>
          <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg">
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
