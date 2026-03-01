@extends('layouts.admin')

@section('page_title', 'Edit User')

@section('content')
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
@endsection
