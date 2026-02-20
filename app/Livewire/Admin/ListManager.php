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

    // Si viene el parámetro setList, activar esa lista y redirigir a home
    if (request()->has('setList')) {
      $listId = (int) request()->get('setList');
      $list = ItemsList::find($listId);

      if ($list && $list->userHasAccess(Auth::user())) {
        Auth::user()->update(['primary_list_id' => $listId]);
        $this->redirect(route('home'), navigate: true);
      }
    }
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

    // Actualizar UI con JavaScript
    $listName = addslashes($list->name);
    $this->js(<<<JS
(function() {
  const headerLink = document.getElementById('active-list-name');
  if (headerLink) {
    headerLink.textContent = '{$listName}';
  }

  const menuButtons = document.querySelectorAll('[data-list-item]');
  menuButtons.forEach(function(btn) {
    btn.classList.remove('bg-blue-50', 'dark:bg-blue-900/30', 'text-blue-600', 'dark:text-blue-400');
    var svg = btn.querySelector('svg');
    if (svg) svg.remove();
  });

  const activeBtn = document.querySelector('[data-list-id="{$listId}"]');
  if (activeBtn) {
    activeBtn.classList.add('bg-blue-50', 'dark:bg-blue-900/30', 'text-blue-600', 'dark:text-blue-400');
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'w-4 h-4 flex-shrink-0 ml-2');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('viewBox', '0 0 24 24');
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('stroke-linecap', 'round');
    path.setAttribute('stroke-linejoin', 'round');
    path.setAttribute('stroke-width', '2');
    path.setAttribute('d', 'M5 13l4 4L19 7');
    svg.appendChild(path);
    activeBtn.appendChild(svg);
  }
})();
JS);
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

  public function confirmDeleteList(int $listId): void {
    $this->dispatch('showConfirmDialog', [
      'title' => '¿Eliminar lista?',
      'message' => '¿Estás seguro de que quieres eliminar esta lista y todos sus items?',
      'confirmText' => 'Sí, eliminar',
      'cancelText' => 'Cancelar',
      'confirmEvent' => 'delete-list-' . $listId,
      'confirmParams' => [$listId],
    ]);
  }

  public function getListeners(): array {
    $listeners = [];
    $ownedLists = Auth::user()->ownedLists;
    foreach ($ownedLists as $list) {
      $listeners['delete-list-' . $list->id] = 'deleteList';
    }
    return $listeners;
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
