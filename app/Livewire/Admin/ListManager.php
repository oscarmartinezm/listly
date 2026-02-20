<?php
namespace App\Livewire\Admin;

use App\Models\ItemsList;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListManager extends Component {
  public string $newListName  = '';
  public ?int $editingListId  = null;
  public string $editListName = '';
  public ?int $activeListId   = null;

  public function mount(): void {
    $this->activeListId = Auth::user()->primary_list_id;
  }

  public function createList(): void {
    $name = trim($this->newListName);
    if ($name === '') {
      return;
    }

    $user = Auth::user();
    $list = ItemsList::create([
      'name'    => $name,
      'user_id' => $user->id,
    ]);

    if (! $user->primary_list_id) {
      $user->update(['primary_list_id' => $list->id]);
      $this->activeListId = $list->id;
    }

    $this->newListName = '';
    $this->js("document.getElementById('input-new-list').value = ''");
  }

  public function setPrimary(int $listId): void {
    $list = ItemsList::findOrFail($listId);
    if (! $list->userHasAccess(Auth::user())) {
      return;
    }

    Auth::user()->update(['primary_list_id' => $listId]);
    $this->activeListId = $listId;
    $this->dispatch('list-changed');
  }

  public function startEdit(int $listId): void {
    $list = ItemsList::findOrFail($listId);
    if (! $list->userIsOwner(Auth::user())) {
      return;
    }

    $this->editingListId = $listId;
    $this->editListName  = $list->name;
  }

  public function saveEdit(): void {
    $name = trim($this->editListName);
    if ($name === '' || ! $this->editingListId) {
      return;
    }

    $list = ItemsList::findOrFail($this->editingListId);
    if (! $list->userIsOwner(Auth::user())) {
      return;
    }

    $list->update(['name' => $name]);
    $this->editingListId = null;
  }

  public function cancelEdit(): void {
    $this->editingListId = null;
  }

  public function deleteList(int $listId): void {
    $list = ItemsList::findOrFail($listId);
    if (! $list->userIsOwner(Auth::user())) {
      return;
    }

    $user = Auth::user();
    $list->delete();

    if ($user->primary_list_id === $listId) {
      $newPrimary = $user->ownedLists()->first() ?? $user->sharedLists()->first();
      $user->update(['primary_list_id' => $newPrimary?->id]);
      $this->activeListId = $newPrimary?->id;
    }
  }

  public function render() {
    $user        = Auth::user();
    $ownedLists  = $user->ownedLists;
    $sharedLists = $user->sharedLists;

    return view('livewire.admin.list-manager', compact('ownedLists', 'sharedLists'));
  }
}
