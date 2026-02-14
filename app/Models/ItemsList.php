<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ItemsList extends Model {
  protected $fillable = ['name', 'user_id', 'share_token'];

  protected static function booted(): void {
    static::creating(function (ItemsList $list) {
      if (empty($list->share_token)) {
        $list->share_token = Str::random(32);
      }
    });
  }

  public function owner(): BelongsTo {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function sharedUsers(): BelongsToMany {
    return $this->belongsToMany(User::class, 'list_shares');
  }

  public function categories(): HasMany {
    return $this->hasMany(Category::class)->orderBy('order');
  }

  public function tags(): HasMany {
    return $this->hasMany(Tag::class)->orderBy('name');
  }

  public function items(): HasMany {
    return $this->hasMany(Item::class);
  }

  public function userHasAccess(User $user): bool {
    return $this->user_id === $user->id
    || $this->sharedUsers()->where('user_id', $user->id)->exists();
  }

  public function userIsOwner(User $user): bool {
    return $this->user_id === $user->id;
  }
}
