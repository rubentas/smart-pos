<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
  public function index()
  {
    $branches = Branch::orderBy('name')->paginate(10);
    return view('admin.branches.index', compact('branches'));
  }

  public function create()
  {
    return view('admin.branches.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'code' => 'required|string|unique:branches,code|max:20',
      'name' => 'required|string|max:255',
      'address' => 'nullable|string',
      'phone' => 'nullable|string|max:20',
      'email' => 'nullable|email|max:255',
      'city' => 'nullable|string|max:100',
      'is_active' => 'boolean'
    ]);

    Branch::create($request->all());

    return redirect()->route('admin.branches.index')
      ->with('success', 'Cabang berhasil ditambahkan');
  }

  public function edit(Branch $branch)
  {
    return view('admin.branches.edit', compact('branch'));
  }

  public function update(Request $request, Branch $branch)
  {
    $request->validate([
      'code' => 'required|string|max:20|unique:branches,code,' . $branch->id,
      'name' => 'required|string|max:255',
      'address' => 'nullable|string',
      'phone' => 'nullable|string|max:20',
      'email' => 'nullable|email|max:255',
      'city' => 'nullable|string|max:100',
      'is_active' => 'boolean'
    ]);

    $branch->update($request->all());

    return redirect()->route('admin.branches.index')
      ->with('success', 'Cabang berhasil diupdate');
  }

  public function destroy(Branch $branch)
  {
    // Cek apakah ada relasi
    if ($branch->users()->count() > 0 || $branch->products()->count() > 0) {
      return back()->with('error', 'Tidak bisa hapus cabang yang masih memiliki data');
    }

    $branch->delete();

    return redirect()->route('admin.branches.index')
      ->with('success', 'Cabang berhasil dihapus');
  }
}