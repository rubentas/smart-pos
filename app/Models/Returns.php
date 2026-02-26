<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
  use HasFactory;

  protected $table = 'returns';

  protected $fillable = [
    'return_no',
    'sale_id',
    'user_id',
    'total_refund',
    'reason',
    'date'
  ];

  protected $casts = [
    'date' => 'date',
    'total_refund' => 'decimal:2'
  ];

  public function sale()
  {
    return $this->belongsTo(Sale::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function details()
  {
    return $this->hasMany(ReturnDetail::class, 'return_id');
  }
}
