<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
  protected $startDate;
  protected $endDate;

  public function __construct($startDate, $endDate)
  {
    $this->startDate = $startDate;
    $this->endDate = $endDate;
  }

  public function collection()
  {
    return Sale::with('user', 'details.product')
      ->whereBetween('date', [$this->startDate, $this->endDate])
      ->where('branch_id', session('active_branch'))
      ->orderBy('date', 'desc')
      ->get();
  }

  public function headings(): array
  {
    return [
      'Tanggal',
      'Invoice',
      'Kasir',
      'Total Item',
      'Subtotal',
      'Diskon',
      'Pajak',
      'Total',
      'Metode Bayar'
    ];
  }

  public function map($sale): array
  {
    return [
      $sale->date->format('d/m/Y H:i'),
      $sale->invoice_no,
      $sale->user->name,
      $sale->details->sum('quantity'),
      $sale->subtotal,
      $sale->discount,
      $sale->tax,
      $sale->total,
      strtoupper($sale->payment_method)
    ];
  }
}
