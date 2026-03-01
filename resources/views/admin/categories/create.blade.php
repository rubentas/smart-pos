@extends('layouts.admin')

@section('page_title', 'Tambah Kategori')

@section('content')
  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6">Tambah Kategori Baru</h2>

      <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Nama Kategori</label>
          <input type="text" name="name" value="{{ old('name') }}"
            class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror" required>
          @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Deskripsi</label>
          <textarea name="description" rows="3"
            class="w-full border rounded-lg px-4 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
          @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-end">
          <a href="{{ route('admin.categories.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg mr-2">
            Batal
          </a>
          <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
