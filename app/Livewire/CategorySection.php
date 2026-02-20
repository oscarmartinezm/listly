<?php
namespace App\Livewire;

use App\Events\ItemCreated;
use App\Events\ListUpdated;
use App\Models\Category;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CategorySection extends Component {
  public Category $category;
  #[Reactive]
  public bool $showChecked;
  #[Reactive]
  public bool $showUnchecked;
  #[Reactive]
  public string $searchText = '';
  #[Reactive]
  public array $activeTagIds = [];
  public bool $collapsed = false;
  public int $version = 0;
  public string $newItemText = '';

  public function toggleCollapse(): void {
    $this->collapsed = ! $this->collapsed;
  }

  public function confirmUncheckAll(): void {
    $this->dispatch('showConfirmDialog', [
      'title' => '¿Desmarcar todos?',
      'message' => '¿Estás seguro de que quieres desmarcar todos los items de esta categoría?',
      'confirmText' => 'Sí, desmarcar',
      'cancelText' => 'Cancelar',
      'confirmEvent' => 'uncheckAll-' . $this->category->id,
    ]);
  }

  public function getListeners(): array {
    return [
      'uncheckAll-' . $this->category->id => 'uncheckAll',
    ];
  }

  public function uncheckAll(): void {
    $this->category->items()->where('is_checked', true)->update(['is_checked' => false]);
    $this->version++;
    ListUpdated::dispatch($this->category->itemsList);
  }

  public function addItem(): void {
    $text = trim($this->newItemText);
    if ($text === '') {
      return;
    }

    $list = $this->category->itemsList;
    $item = $list->items()->create([
      'text'        => $text,
      'category_id' => $this->category->id,
      'order'       => $list->items()->max('order') + 1,
    ]);

    ItemCreated::dispatch($item);
    $this->newItemText = '';
    $this->js("document.getElementById('input-add-item-{$this->category->id}').value = ''");
    $this->dispatch('item-created');
  }

  public function render() {
    // Cargar items desde la relación (ya cargados por ItemsListView)
    $items = $this->category->items;

    // Aplicar filtro por tags
    if (! empty($this->activeTagIds)) {
      $items = $items->filter(function ($item) {
        return $item->tags->whereIn('id', $this->activeTagIds)->isNotEmpty();
      });
    }

    // Aplicar filtro por texto del filtro global (mínimo 3 caracteres)
    if (strlen($this->searchText) >= 3) {
      $search = strtolower($this->searchText);
      $items = $items->filter(function ($item) use ($search) {
        return str_contains(strtolower($item->text), $search);
      });
    }

    if (! $this->showChecked) {
      $items = $items->where('is_checked', false)->values();
    }

    if (! $this->showUnchecked) {
      $items = $items->where('is_checked', true)->values();
    }

    return view('livewire.category-section', compact('items'));
  }
}
