<?php
namespace App\Livewire;

use App\Events\ItemCreated;
use App\Models\Item;
use App\Models\ItemsList;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class UncategorizedSection extends Component {
  #[Reactive]
  public ItemsList $list;
  #[Reactive]
  public bool $showChecked;
  #[Reactive]
  public bool $showUnchecked;
  public bool $showCollapsible = false;
  public int $version = 0;
  public string $newItemText = '';

  public function addItem(): void {
    $text = trim($this->newItemText);
    if ($text === '') {
      return;
    }

    $item = $this->list->items()->create([
      'text'        => $text,
      'category_id' => null,
      'order'       => $this->list->items()->max('order') + 1,
    ]);

    ItemCreated::dispatch($item);
    $this->newItemText = '';
    $this->js("document.getElementById('input-add-uncategorized').value = ''");
    $this->dispatch('item-created');
  }

  public function render() {
    // Cargar items sin categoría
    $items = $this->list->items()
      ->whereNull('category_id')
      ->with('tags')
      ->orderBy('is_checked')
      ->orderByRaw('LOWER(text)')
      ->get();

    if (! $this->showChecked) {
      $items = $items->where('is_checked', false)->values();
    }

    if (! $this->showUnchecked) {
      $items = $items->where('is_checked', true)->values();
    }

    return view('livewire.uncategorized-section', compact('items'));
  }
}
