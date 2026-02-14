<?php
namespace App\Livewire;

use App\Events\ItemCreated;
use App\Events\ListUpdated;
use App\Models\Category;
use App\Models\Item;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CategorySection extends Component {
  public Category $category;
  #[Reactive]
  public bool $showChecked;
  public bool $collapsed = false;
  public array $itemIds = [];
  public int $version = 0;
  public string $newItemText = '';

  public function mount(): void {
    $this->loadItems();
  }

  public function toggleCollapse(): void {
    $this->collapsed = ! $this->collapsed;
  }

  public function uncheckAll(): void {
    $this->category->items()->where('is_checked', true)->update(['is_checked' => false]);
    $this->version++;
    ListUpdated::dispatch($this->category->shoppingList);
  }

  #[On('item-changed')]
  public function onItemChanged(): void {
    // Re-render para actualizar visibilidad del boton Deseleccionar
  }

  public function addItem(): void {
    $text = trim($this->newItemText);
    if ($text === '') {
      return;
    }

    $list = $this->category->shoppingList;
    $item = $list->items()->create([
      'text'        => $text,
      'category_id' => $this->category->id,
      'order'       => $list->items()->max('order') + 1,
    ]);

    ItemCreated::dispatch($item);
    $this->newItemText = '';
    $this->js("document.getElementById('input-add-item-{$this->category->id}').value = ''");
    $this->loadItems();
    $this->dispatch('item-created');
  }

  public function reloadCategory(): void {
    $this->loadItems();
  }

  private function loadItems(): void {
    $this->itemIds = $this->category->items()
      ->orderBy('is_checked')
      ->orderByRaw('LOWER(text)')
      ->pluck('id')
      ->toArray();
  }

  public function render() {
    $items = Item::whereIn('id', $this->itemIds)
      ->with('tags')
      ->get()
      ->sortBy(function ($item) {
        return array_search($item->id, $this->itemIds);
      })
      ->values();

    if (! $this->showChecked) {
      $items = $items->where('is_checked', false)->values();
    }

    return view('livewire.category-section', compact('items'));
  }
}
