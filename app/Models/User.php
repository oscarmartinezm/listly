<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
  use HasFactory, Notifiable;

  protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',
    'avatar',
    'primary_list_id',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array {
    return [
      'email_verified_at' => 'datetime',
      'password'          => 'hashed',
    ];
  }

  public function ownedLists(): HasMany {
    return $this->hasMany(ItemsList::class);
  }

  public function sharedLists(): BelongsToMany {
    return $this->belongsToMany(ItemsList::class, 'list_shares');
  }

  public function allLists() {
    return $this->ownedLists->merge($this->sharedLists);
  }

  public function primaryList(): BelongsTo {
    return $this->belongsTo(ItemsList::class, 'primary_list_id');
  }
}
