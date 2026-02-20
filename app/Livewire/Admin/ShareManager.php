<?php
namespace App\Livewire\Admin;

use App\Models\ItemsList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class ShareManager extends Component {
  public int $listId;

  public function confirmRegenerateToken(): void {
    $this->dispatch('showConfirmDialog', [
      'title' => '¿Regenerar link?',
      'message' => '¿Estás seguro? El link anterior dejará de funcionar.',
      'confirmText' => 'Sí, regenerar',
      'cancelText' => 'Cancelar',
      'confirmEvent' => 'regenerate-token-' . $this->listId,
    ]);
  }

  public function confirmRemoveUser(int $userId, string $userName): void {
    $this->dispatch('showConfirmDialog', [
      'title' => '¿Quitar acceso?',
      'message' => '¿Estás seguro de que quieres quitar acceso a ' . $userName . '?',
      'confirmText' => 'Sí, quitar',
      'cancelText' => 'Cancelar',
      'confirmEvent' => 'remove-user-' . $userId,
      'confirmParams' => [$userId],
    ]);
  }

  public function getListeners(): array {
    $listeners = ['regenerate-token-' . $this->listId => 'regenerateToken'];

    $list = ItemsList::with('sharedUsers')->findOrFail($this->listId);
    foreach ($list->sharedUsers as $user) {
      $listeners['remove-user-' . $user->id] = 'removeUser';
    }

    return $listeners;
  }

  public function regenerateToken(): void {
    $list = ItemsList::findOrFail($this->listId);
    if (! $list->userIsOwner(Auth::user())) {
      return;
    }

    $list->update(['share_token' => Str::random(32)]);
  }

  public function removeUser(int $userId): void {
    $list = ItemsList::findOrFail($this->listId);
    if (! $list->userIsOwner(Auth::user())) {
      return;
    }

    $list->sharedUsers()->detach($userId);
  }

  public function render() {
    $list     = ItemsList::with('sharedUsers')->findOrFail($this->listId);
    $isOwner  = $list->userIsOwner(Auth::user());
    $shareUrl = $isOwner ? route('share.accept', $list->share_token) : null;

    $listName = $list->name;
    return view('livewire.admin.share-manager', compact('list', 'isOwner', 'shareUrl', 'listName'));
  }
}
