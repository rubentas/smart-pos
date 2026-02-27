<?php

namespace App\Models;

use App\Models\PurchaseDetail;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
  protected $fillable = [
    'invoice_no',
    'supplier_id',
    'user_id',
    'date',
    'total',
    'notes'
  ];

  protected $casts = [
    'date' => 'date',
  ];

  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function details()
  {
    return $this->hasMany(PurchaseDetail::class);
  }

  public function branch()
  {
    return $this->belongsTo(Branch::class);
  }
}
