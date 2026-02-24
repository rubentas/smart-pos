<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User - Smart POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between">
      <h1 class="text-xl font-bold">Smart POS - Edit User</h1>
      <div>
        <span class="mr-4">{{ Auth::user()->name }} ({{ Auth::user()->role->name }})</span>
        <a href="{{ route('admin.dashboard') }}" class="mr-4 text-blue-500">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="text-red-500">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6">Edit User: {{ $user->name }}</h2>

      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Nama</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}"
            class="w-full border rounded-lg px-4 py-2 @error('name') border-red-500 @enderror">
          @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}"
            class="w-full border rounded-lg px-4 py-2 @error('email') border-red-500 @enderror">
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Password (Kosongkan jika tidak diubah)</label>
          <input type="password" name="password"
            class="w-full border rounded-lg px-4 py-2 @error('password') border-red-500 @enderror">
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Role</label>
          <select name="role_id" class="w-full border rounded-lg px-4 py-2 @error('role_id') border-red-500 @enderror">
            <option value="">Pilih Role</option>
            @foreach ($roles as $role)
              <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          @error('role_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex justify-end">
          <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg mr-2">
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
