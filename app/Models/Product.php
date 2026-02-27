<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'name',
    'barcode',
    'category_id',
    'purchase_price',
    'selling_price',
    'stock',
    'min_stock',
    'image'
  ];

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function saleDetails()
  {
    return $this->hasMany(SaleDetail::class);
  }

  public function stockLogs()
  {
    return $this->hasMany(StockLog::class);
  }

  public function returnDetails()
  {
    return $this->hasMany(ReturnDetail::class);
  }

  public function branch()
  {
    return $this->belongsTo(Branch::class);
  }
}