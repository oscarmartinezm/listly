<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tags', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('color')->default('#3b82f6');
      $table->foreignId('items_list_id')->constrained()->cascadeOnDelete();
      $table->timestamps();
      $table->unique(['items_list_id', 'name']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tags');
  }
};
