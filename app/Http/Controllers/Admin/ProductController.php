<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index()
  {
    $products = Product::with('category')->paginate(10);
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

    Product::create($request->all());

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