<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
  use HasFactory;

  protected $fillable = [
    'return_id',
    'product_id',
    'sale_detail_id',
    'quantity',
    'price',
    'subtotal'
  ];

  protected $casts = [
    'price' => 'decimal:2',
    'subtotal' => 'decimal:2'
  ];

  public function returns()
  {
    return $this->belongsTo(Returns::class, 'return_id');
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function saleDetail()
  {
    return $this->belongsTo(SaleDetail::class);
  }
}