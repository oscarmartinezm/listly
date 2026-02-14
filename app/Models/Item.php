<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model {
  protected $fillable = ['text', 'shopping_list_id', 'category_id', 'is_checked', 'link', 'order'];

  protected function casts(): array {
    return [
      'is_checked' => 'boolean',
    ];
  }

  public function shoppingList(): BelongsTo {
    return $this->belongsTo(ShoppingList::class);
  }

  public function category(): BelongsTo {
    return $this->belongsTo(Category::class);
  }

  public function tags(): BelongsToMany {
    return $this->belongsToMany(Tag::class);
  }
}
