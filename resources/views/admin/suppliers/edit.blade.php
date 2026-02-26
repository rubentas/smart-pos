<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Supplier - Smart POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between">
      <h1 class="text-xl font-bold">Smart POS - Edit Supplier</h1>
      <div>
        <span class="mr-4">{{ Auth::user()->name }} ({{ Auth::user()->role->name }})</span>
        <a href="{{ route('admin.dashboard') }}" class="mr-4 text-blue-500">Dashboard</a>
        <a href="{{ route('admin.suppliers.index') }}" class="mr-4 text-blue-500">Supplier</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="text-red-500">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6">Edit Supplier: {{ $supplier->name }}</h2>

      <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Nama Supplier</label>
            <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
              class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror" required>
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Nama Kontak</label>
            <input type="text" name="contact" value="{{ old('contact', $supplier->contact) }}"
              class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
              class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
              class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">NPWP</label>
            <input type="text" name="tax_number" value="{{ old('tax_number', $supplier->tax_number) }}"
              class="w-full border rounded-lg px-4 py-2">
          </div>

          <div class="mb-4 col-span-2">
            <label class="block text-sm font-medium mb-2">Alamat</label>
            <textarea name="address" rows="3" class="w-full border rounded-lg px-4 py-2">{{ old('address', $supplier->address) }}</textarea>
          </div>
        </div>

        <div class="flex justify-end">
          <a href="{{ route('admin.suppliers.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg mr-2">
            Batal
          </a>
          <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg">
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>
