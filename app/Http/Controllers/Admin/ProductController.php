<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
  public function index()
  {
    $products = Product::with('category', 'supplier')
      ->where('branch_id', session('active_branch'))
      ->orderBy('name')
      ->paginate(15);
    return view('admin.products.index', compact('products'));
  }

  public function create()
  {
    $categories = Category::all();
    return view('admin.products.create', compact('categories'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'barcode' => 'required|string|unique:products',
      'category_id' => 'required|exists:categories,id',
      'purchase_price' => 'required|numeric|min:0',
      'selling_price' => 'required|numeric|min:0|gt:purchase_price',
      'stock' => 'required|integer|min:0',
      'min_stock' => 'required|integer|min:0',
    ]);

    $product = Product::create([
      'branch_id' => session('active_branch'),
      'category_id' => $request->category_id,
      'name' => $request->name,
      'barcode' => $request->barcode,
      'purchase_price' => $request->purchase_price,
      'selling_price' => $request->selling_price,
      'stock' => $request->stock,
      'min_stock' => $request->min_stock,
    ]);

    return redirect()->route('admin.products.index')
      ->with('success', 'Produk berhasil ditambahkan');
  }

  public function edit(Product $product)
  {
    $categories = Category::all();
    return view('admin.products.edit', compact('product', 'categories'));
  }

  public function update(Request $request, Product $product)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'barcode' => 'required|string|unique:products,barcode,' . $product->id,
      'category_id' => 'required|exists:categories,id',
      'purchase_price' => 'required|numeric|min:0',
      'selling_price' => 'required|numeric|min:0|gt:purchase_price',
      'stock' => 'required|integer|min:0',
      'min_stock' => 'required|integer|min:0',
    ]);

    $product->update($request->all());

    return redirect()->route('admin.products.index')
      ->with('success', 'Produk berhasil diupdate');
  }

  public function destroy(Product $product)
  {
    $product->delete();

    return redirect()->route('admin.products.index')
      ->with('success', 'Produk berhasil dihapus');
  }
}