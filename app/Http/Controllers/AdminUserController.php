<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('role:Admin');
  }
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $users = User::with('role')->paginate(10);
    return view('admin.users.index', compact('users'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $roles = Role::all();
    return view('admin.users.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(UserRequest $request)
  {
    $data = $request->validated();
    $data['password'] = Hash::make($data['password']);

    User::create($data);

    return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(User $user)
  {
    $roles = Role::all();
    return view('admin.users.edit', compact('user', 'roles'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UserRequest $request, User $user)
  {
    $data = $request->validated();
    if ($request->filled('password')) {
      $data['password'] = Hash::make($data['password']);
    } else {
      unset($data['password']);
    }

    $user->update($data);

    return redirect()->route('admin.users.index')->with('success', 'user berhasil diupdate');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $user)
  {
    if ($user->id === auth()->id()) {
      return back()->with('error', 'Anda tidak bisa menghapus diri sendiri ');
    }
    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'User berhasil di hapus');
  }
}