<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
  protected $fillable = ['name', 'color', 'shopping_list_id'];

  public function shoppingList(): BelongsTo
  {
    return $this->belongsTo(ShoppingList::class);
  }

  public function items(): BelongsToMany
  {
    return $this->belongsToMany(Item::class);
  }
}
