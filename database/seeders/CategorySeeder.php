<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    $categories = [
      ['name' => 'Makanan', 'description' => 'Produk makanan ringan & berat'],
      ['name' => 'Minuman', 'description' => 'Produk minuman kemasan'],
      ['name' => 'Alat Tulis', 'description' => 'Peralatan kantor & sekolah'],
      ['name' => 'Elektronik', 'description' => 'Aksesoris elektronik'],
    ];

    foreach ($categories as $category) {
      \App\Models\Category::create($category);
    }
  }
}