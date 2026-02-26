<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
  /**
   * Display a listing of users.
   */
  public function index(): View
  {
    $users = User::with('role')->paginate(10);

    return view('admin.users.index', compact('users'));
  }

  /**
   * Show form for creating new user.
   */
  public function create(): View
  {
    $roles = Role::all();

    return view('admin.users.create', compact('roles'));
  }

  /**
   * Store newly created user.
   */
  public function store(UserRequest $request): RedirectResponse
  {
    $data = $request->validated();
    $data['password'] = Hash::make($data['password']);

    User::create($data);

    return redirect()
      ->route('admin.users.index')
      ->with('success', 'User berhasil ditambahkan');
  }

  /**
   * Show form for editing user.
   */
  public function edit(User $user): View
  {
    $roles = Role::all();

    return view('admin.users.edit', compact('user', 'roles'));
  }

  /**
   * Update specified user.
   */
  public function update(UserRequest $request, User $user): RedirectResponse
  {
    $data = $request->validated();

    if ($request->filled('password')) {
      $data['password'] = Hash::make($data['password']);
    } else {
      unset($data['password']);
    }

    $user->update($data);

    return redirect()
      ->route('admin.users.index')
      ->with('success', 'User berhasil diupdate');
  }

  /**
   * Delete specified user.
   */
  public function destroy(User $user): RedirectResponse
  {
    if ($user->id === auth()->id()) {
      return back()->with('error', 'Tidak dapat menghapus akun sendiri');
    }

    $user->delete();

    return redirect()
      ->route('admin.users.index')
      ->with('success', 'User berhasil dihapus');
  }
}