<?php

namespace App\Models;

use App\Models\ReturnModel;
use App\Models\SaleDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
  use HasFactory;

  protected $fillable = [
    'invoice_no',
    'user_id',
    'subtotal',
    'discount',
    'tax',
    'total',
    'payment_method',
    'payment_status',
    'date'
  ];

  protected $casts = [
    'date' => 'date',
    'subtotal' => 'decimal:2',
    'discount' => 'decimal:2',
    'tax' => 'decimal:2',
    'total' => 'decimal:2'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function details()
  {
    return $this->hasMany(SaleDetail::class);
  }

  public function returns()
  {
    return $this->hasMany(ReturnModel::class);
  }
}