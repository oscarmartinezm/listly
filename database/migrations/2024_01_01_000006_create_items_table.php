<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('items', function (Blueprint $table) {
      $table->id();
      $table->string('text');
      $table->foreignId('shopping_list_id')->constrained()->cascadeOnDelete();
      $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
      $table->boolean('is_checked')->default(false);
      $table->string('link')->nullable();
      $table->integer('order')->default(0);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('items');
  }
};
