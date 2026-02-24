<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between">
      <h1 class="text-xl font-bold">Smart POS</h1>
      <div>
        <span class="mr-4">{{ Auth::user()->name }} ({{ Auth::user()->role->name }})</span>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="text-red-500">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Dashboard Admin</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold text-lg mb-2">Total Users</h3>
        <p class="text-3xl text-blue-600">{{ \App\Models\User::count() }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold text-lg mb-2">Total Products</h3>
        <p class="text-3xl text-green-600">0</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold text-lg mb-2">Today's Sales</h3>
        <p class="text-3xl text-purple-600">Rp 0</p>
      </div>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow p-6">
      <h3 class="font-semibold text-lg mb-4">Admin Menu</h3>
      <ul class="space-y-2">
        <li><a href="#" class="text-blue-500 hover:underline">Manajemen User</a></li>
        <li><a href="#" class="text-blue-500 hover:underline">Manajemen Produk</a></li>
        <li><a href="#" class="text-blue-500 hover:underline">Manajemen Supplier</a></li>
        <li><a href="#" class="text-blue-500 hover:underline">Laporan</a></li>
      </ul>
    </div>
  </div>
</body>

</html>
