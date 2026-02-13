<?php

namespace App\Livewire\Admin;

use App\Models\ShoppingList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class ShareManager extends Component
{
  public int $listId;

  public function regenerateToken(): void
  {
    $list = ShoppingList::findOrFail($this->listId);
    if (!$list->userIsOwner(Auth::user())) return;

    $list->update(['share_token' => Str::random(32)]);
  }

  public function removeUser(int $userId): void
  {
    $list = ShoppingList::findOrFail($this->listId);
    if (!$list->userIsOwner(Auth::user())) return;

    $list->sharedUsers()->detach($userId);
  }

  public function render()
  {
    $list = ShoppingList::with('sharedUsers')->findOrFail($this->listId);
    $isOwner = $list->userIsOwner(Auth::user());
    $shareUrl = $isOwner ? route('share.accept', $list->share_token) : null;

    $listName = $list->name;
    return view('livewire.admin.share-manager', compact('list', 'isOwner', 'shareUrl', 'listName'));
  }
}
