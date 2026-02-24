<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen User - Smart POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
  <nav class="bg-white shadow-lg p-4">
    <div class="container mx-auto flex justify-between">
      <h1 class="text-xl font-bold">Smart POS - Manajemen User</h1>
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
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if (session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
      </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="p-6 border-b flex justify-between items-center">
        <h2 class="text-2xl font-bold">Daftar User</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          + Tambah User
        </a>
      </div>

      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach ($users as $user)
            <tr>
              <td class="px-6 py-4">{{ $user->id }}</td>
              <td class="px-6 py-4">{{ $user->name }}</td>
              <td class="px-6 py-4">{{ $user->email }}</td>
              <td class="px-6 py-4">
                <span
                  class="px-2 py-1 text-xs rounded
                                @if ($user->role->name == 'Admin') bg-red-100 text-red-800
                                @elseif($user->role->name == 'Owner') bg-purple-100 text-purple-800
                                @else bg-green-100 text-green-800 @endif">
                  {{ $user->role->name }}
                </span>
              </td>
              <td class="px-6 py-4">
                <a href="{{ route('admin.users.edit', $user) }}"
                  class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>

                @if ($user->id !== Auth::id())
                  <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700"
                      onclick="return confirm('Yakin ingin menghapus?')">
                      Hapus
                    </button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="p-6">
        {{ $users->links() }}
      </div>
    </div>
  </div>
</body>

</html>
