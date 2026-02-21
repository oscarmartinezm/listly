<?php
namespace App\Livewire;

use App\Events\ItemCreated;
use App\Events\ListUpdated;
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
  #[Reactive]
  public string $searchText = '';
  #[Reactive]
  public array $activeTagIds = [];
  public bool $showCollapsible = false;
  public int $version = 0;
  public string $newItemText = '';

  public function confirmUncheckAll(): void {
    $this->dispatch('showConfirmDialog', [
      'title' => '¿Desmarcar todos?',
      'message' => '¿Estás seguro de que quieres desmarcar todos los items sin categoría?',
      'confirmText' => 'Sí, desmarcar',
      'cancelText' => 'Cancelar',
      'confirmEvent' => 'uncheckAll-uncategorized-' . $this->list->id,
    ]);

    $this->skipRender();
  }

  public function getListeners(): array {
    return [
      'uncheckAll-uncategorized-' . $this->list->id => 'uncheckAll',
    ];
  }

  public function uncheckAll(): void {
    $this->list->items()->whereNull('category_id')->where('is_checked', true)->update(['is_checked' => false]);

    // Recargar items con el orden correcto
    $this->list->load(['items' => function ($query) {
      $query->with('tags')->orderBy('is_checked')->orderByRaw('LOWER(text)');
    }]);

    $this->version++;
    ListUpdated::dispatch($this->list);
  }

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

    // Aplicar filtro por tags
    if (! empty($this->activeTagIds)) {
      $items = $items->filter(function ($item) {
        return $item->tags->whereIn('id', $this->activeTagIds)->isNotEmpty();
      });
    }

    // Aplicar filtro por texto (mínimo 3 caracteres)
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

    return view('livewire.uncategorized-section', compact('items'));
  }
}
