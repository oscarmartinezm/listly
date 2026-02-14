<?php
namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\ShoppingList;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CategoryManager extends Component {
  public int $listId;
  public string $newName  = '';
  public ?int $editingId  = null;
  public string $editName = '';

  public function create(): void {
    $name = trim($this->newName);
    if ($name === '') {
      return;
    }

    $list = ShoppingList::findOrFail($this->listId);
    if (! $list->userHasAccess(Auth::user())) {
      return;
    }

    $list->categories()->create([
      'name'  => $name,
      'order' => $list->categories()->max('order') + 1,
    ]);
    $this->newName = '';
    $this->js("document.getElementById('input-new-category').value = ''");
  }

  public function startEdit(int $id): void {
    $cat             = Category::findOrFail($id);
    $this->editingId = $id;
    $this->editName  = $cat->name;
  }

  public function saveEdit(): void {
    $name = trim($this->editName);
    if ($name === '' || ! $this->editingId) {
      return;
    }

    Category::where('id', $this->editingId)->update(['name' => $name]);
    $this->editingId = null;
  }

  public function cancelEdit(): void {
    $this->editingId = null;
  }

  public function delete(int $id): void {
    Category::where('id', $id)
      ->where('shopping_list_id', $this->listId)
      ->delete();
  }

  public function moveUp(int $id): void {
    $cat  = Category::findOrFail($id);
    $prev = Category::where('shopping_list_id', $this->listId)
      ->where('order', '<', $cat->order)
      ->orderByDesc('order')
      ->first();
    if ($prev) {
      $tmpOrder = $cat->order;
      $cat->update(['order' => $prev->order]);
      $prev->update(['order' => $tmpOrder]);
    }
  }

  public function moveDown(int $id): void {
    $cat  = Category::findOrFail($id);
    $next = Category::where('shopping_list_id', $this->listId)
      ->where('order', '>', $cat->order)
      ->orderBy('order')
      ->first();
    if ($next) {
      $tmpOrder = $cat->order;
      $cat->update(['order' => $next->order]);
      $next->update(['order' => $tmpOrder]);
    }
  }

  public function render() {
    $categories = Category::where('shopping_list_id', $this->listId)
      ->orderBy('order')
      ->get();
    $listName = ShoppingList::findOrFail($this->listId)->name;

    return view('livewire.admin.category-manager', compact('categories', 'listName'));
  }
}
