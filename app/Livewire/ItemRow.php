<?php
namespace App\Livewire;

use App\Events\ItemDeleted as ItemDeletedEvent;
use App\Events\ItemUpdated;
use App\Models\Item;
use Livewire\Component;

class ItemRow extends Component {
  public Item $item;
  public bool $editing    = false;
  public string $editText = '';

  public function toggleCheck(): void {
    $this->item->update(['is_checked' => ! $this->item->is_checked]);
    ItemUpdated::dispatch($this->item);
  }

  public function startEdit(): void {
    $this->editText = $this->item->text;
    $this->editing  = true;
  }

  public function saveEdit(): void {
    $this->editText = trim($this->editText);
    if ($this->editText !== '') {
      $this->item->update(['text' => $this->editText]);
      ItemUpdated::dispatch($this->item);
      $this->dispatch('item-changed');
    }
    $this->editing = false;
  }

  public function cancelEdit(): void {
    $this->editing = false;
  }

  public function delete(): void {
    $listId = $this->item->items_list_id;
    $itemId = $this->item->id;
    $this->item->delete();
    ItemDeletedEvent::dispatch($itemId, $listId);
    $this->dispatch('item-deleted');
  }

  public function render() {
    return view('livewire.item-row');
  }
}
