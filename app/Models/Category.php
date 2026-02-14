<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model {
  protected $fillable = ['name', 'items_list_id', 'order'];

  public function itemsList(): BelongsTo {
    return $this->belongsTo(ItemsList::class);
  }

  public function items(): HasMany {
    return $this->hasMany(Item::class);
  }
}
