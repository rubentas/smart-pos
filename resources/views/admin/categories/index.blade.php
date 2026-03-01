@extends('layouts.admin')

@section('page_title', 'Kategori')

@section('content')
  <div class="container mx-auto p-6">
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar Kategori</h2>
        <a href="{{ route('admin.categories.create') }}"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          + Tambah Kategori
        </a>
      </div>

      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Produk</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach ($categories as $category)
            <tr>
              <td class="px-6 py-4">{{ $category->id }}</td>
              <td class="px-6 py-4">{{ $category->name }}</td>
              <td class="px-6 py-4">{{ $category->description ?? '-' }}</td>
              <td class="px-6 py-4">{{ $category->products->count() }}</td>
              <td class="px-6 py-4">
                <a href="{{ route('admin.categories.edit', $category) }}"
                  class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
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
        {{ $categories->links() }}
      </div>
    </div>
  </div>
@endsection
