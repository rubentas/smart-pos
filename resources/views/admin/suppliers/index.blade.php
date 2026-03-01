@extends('layouts.admin')

@section('page_title', 'Supplier')

@section('content')
  <div class="container mx-auto p-6">
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar Supplier</h2>
        <a href="{{ route('admin.suppliers.create') }}"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          + Tambah Supplier
        </a>
      </div>

      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NPWP</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach ($suppliers as $supplier)
            <tr>
              <td class="px-6 py-4">{{ $supplier->id }}</td>
              <td class="px-6 py-4">{{ $supplier->name }}</td>
              <td class="px-6 py-4">{{ $supplier->contact ?? '-' }}</td>
              <td class="px-6 py-4">{{ $supplier->phone ?? '-' }}</td>
              <td class="px-6 py-4">{{ $supplier->email ?? '-' }}</td>
              <td class="px-6 py-4">{{ $supplier->tax_number ?? '-' }}</td>
              <td class="px-6 py-4">
                <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                  class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline">
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
        {{ $suppliers->links() }}
      </div>
    </div>
  </div>
@endsection
