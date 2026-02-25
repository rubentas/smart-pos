<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    $products = [
      [
        'name' => 'Indomie Goreng',
        'barcode' => '8991002101234',
        'category_id' => 1,
        'purchase_price' => 2500,
        'selling_price' => 3000,
        'stock' => 100,
        'min_stock' => 10,
      ],
      [
        'name' => 'Teh Botol',
        'barcode' => '8992002101234',
        'category_id' => 2,
        'purchase_price' => 3000,
        'selling_price' => 3500,
        'stock' => 50,
        'min_stock' => 5,
      ],
    ];

    foreach ($products as $product) {
      \App\Models\Product::create($product);
    }
  }
}