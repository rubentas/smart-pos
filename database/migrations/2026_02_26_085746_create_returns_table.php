<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('returns', function (Blueprint $table) {
      $table->id();
      $table->string('return_no')->unique();
      $table->foreignId('sale_id')->constrained()->onDelete('restrict');
      $table->foreignId('user_id')->constrained()->onDelete('restrict');
      $table->decimal('total_refund', 15, 2);
      $table->text('reason');
      $table->date('date');
      $table->timestamps();

      $table->index('return_no');
      $table->index('date');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('returns');
  }
};