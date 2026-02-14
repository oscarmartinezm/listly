<?php
namespace App\Livewire\Admin;

use App\Models\ItemsList;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TagManager extends Component {
  public int $listId;
  public string $newName   = '';
  public string $newColor  = '#3b82f6';
  public ?int $editingId   = null;
  public string $editName  = '';
  public string $editColor = '#3b82f6';

  public function create(): void {
    $name = trim($this->newName);
    if ($name === '') {
      return;
    }

    $list = ItemsList::findOrFail($this->listId);
    if (! $list->userHasAccess(Auth::user())) {
      return;
    }

    $list->tags()->create([
      'name'  => $name,
      'color' => $this->newColor,
    ]);
    $this->newName  = '';
    $this->newColor = '#3b82f6';
  }

  public function startEdit(int $id): void {
    $tag             = Tag::findOrFail($id);
    $this->editingId = $id;
    $this->editName  = $tag->name;
    $this->editColor = $tag->color;
  }

  public function saveEdit(): void {
    $name = trim($this->editName);
    if ($name === '' || ! $this->editingId) {
      return;
    }

    Tag::where('id', $this->editingId)->update([
      'name'  => $name,
      'color' => $this->editColor,
    ]);
    $this->editingId = null;
  }

  public function cancelEdit(): void {
    $this->editingId = null;
  }

  public function delete(int $id): void {
    Tag::where('id', $id)
      ->where('items_list_id', $this->listId)
      ->delete();
  }

  public function render() {
    $tags = Tag::where('items_list_id', $this->listId)
      ->orderBy('name')
      ->get();
    $listName = ItemsList::findOrFail($this->listId)->name;

    return view('livewire.admin.tag-manager', compact('tags', 'listName'));
  }
}
