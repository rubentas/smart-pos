@extends('layouts.admin')

@section('page_title', 'Tambah Supplier')

@section('content')
  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6">Tambah Supplier Baru</h2>

      <form action="{{ route('admin.suppliers.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Nama Supplier</label>
            <input type="text" name="name" value="{{ old('name') }}"
              class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror" required>
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Nama Kontak</label>
            <input type="text" name="contact" value="{{ old('contact') }}"
              class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
              class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">NPWP</label>
            <input type="text" name="tax_number" value="{{ old('tax_number') }}"
              class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4 col-span-2">
            <label class="block text-sm font-medium mb-2">Alamat</label>
            <textarea name="address" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('address') }}</textarea>
          </div>
        </div>

        <div class="flex justify-end">
          <a href="{{ route('admin.suppliers.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg mr-2">
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
