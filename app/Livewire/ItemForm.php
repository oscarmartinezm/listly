<?php
namespace App\Livewire;

use App\Events\ItemCreated;
use App\Models\ItemsList;
use Livewire\Component;

class ItemForm extends Component {
  public ItemsList $list;
  public string $text             = '';
  public ?int $categoryId         = null;
  public string $duplicateWarning = '';

  public function updatedText(): void {
    $this->duplicateWarning = '';
    if (strlen(trim($this->text)) >= 2) {
      $exists = $this->list->items()
        ->whereRaw('LOWER(text) = ?', [strtolower(trim($this->text))])
        ->first();
      if ($exists) {
        $this->duplicateWarning = "\"" . $exists->text . "\" ya existe en la lista" .
          ($exists->is_checked ? ' (comprado)' : '');
      }
    }
  }

  public function addItem(): void {
    $text = trim($this->text);
    if ($text === '') {
      return;
    }

    $item = $this->list->items()->create([
      'text'        => $text,
      'category_id' => $this->categoryId,
      'order'       => $this->list->items()->max('order') + 1,
    ]);

    ItemCreated::dispatch($item);
    $this->text             = '';
    $this->duplicateWarning = '';
    $this->dispatch('item-created');
  }

  public function render() {
    $categories = $this->list->categories;
    return view('livewire.item-form', compact('categories'));
  }
}
