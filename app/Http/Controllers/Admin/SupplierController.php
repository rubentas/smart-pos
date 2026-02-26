<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
  public function index()
  {
    $suppliers = Supplier::paginate(10);
    return view('admin.suppliers.index', compact('suppliers'));
  }

  public function create()
  {
    return view('admin.suppliers.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'contact' => 'nullable|string|max:255',
      'phone' => 'nullable|string|max:20',
      'email' => 'nullable|email|max:255',
      'tax_number' => 'nullable|string|max:50',
      'address' => 'nullable|string',
    ]);

    Supplier::create($request->all());

    return redirect()->route('admin.suppliers.index')
      ->with('success', 'Supplier berhasil ditambahkan');
  }

  public function edit(Supplier $supplier)
  {
    return view('admin.suppliers.edit', compact('supplier'));
  }

  public function update(Request $request, Supplier $supplier)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'contact' => 'nullable|string|max:255',
      'phone' => 'nullable|string|max:20',
      'email' => 'nullable|email|max:255',
      'tax_number' => 'nullable|string|max:50',
      'address' => 'nullable|string',
    ]);

    $supplier->update($request->all());

    return redirect()->route('admin.suppliers.index')
      ->with('success', 'Supplier berhasil diupdate');
  }

  public function destroy(Supplier $supplier)
  {
    $supplier->delete();

    return redirect()->route('admin.suppliers.index')
      ->with('success', 'Supplier berhasil dihapus');
  }
}