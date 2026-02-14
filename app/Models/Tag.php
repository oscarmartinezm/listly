<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model {
  protected $fillable = ['name', 'color', 'items_list_id'];

  public function itemsList(): BelongsTo {
    return $this->belongsTo(ItemsList::class);
  }

  public function items(): BelongsToMany {
    return $this->belongsToMany(Item::class);
  }
}
