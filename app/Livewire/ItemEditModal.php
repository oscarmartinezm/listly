<?php
namespace App\Livewire;

use App\Events\ItemUpdated;
use App\Models\Item;
use App\Models\ItemsList;
use Livewire\Attributes\On;
use Livewire\Component;

class ItemEditModal extends Component {
  public bool $show            = false;
  public ?int $itemId          = null;
  public string $text          = '';
  public ?int $categoryId      = null;
  public string $link          = '';
  public array $selectedTagIds = [];
  public ?ItemsList $list   = null;

  #[On('open-edit-modal')]
  public function openModal(int $itemId): void {
    $item                 = Item::with('tags')->findOrFail($itemId);
    $this->itemId         = $item->id;
    $this->text           = $item->text;
    $this->categoryId     = $item->category_id;
    $this->link           = $item->link ?? '';
    $this->selectedTagIds = $item->tags->pluck('id')->toArray();
    $this->list           = $item->itemsList;
    $this->show           = true;
  }

  public function save(): void {
    $this->text = trim($this->text);
    if ($this->text === '' || ! $this->itemId) {
      return;
    }

    $item = Item::findOrFail($this->itemId);
    $item->update([
      'text'        => $this->text,
      'category_id' => $this->categoryId ?: null,
      'link'        => $this->link !== '' ? $this->link : null,
    ]);
    $item->tags()->sync($this->selectedTagIds);
    ItemUpdated::dispatch($item);

    $this->show = false;
    $this->dispatch('item-changed');
  }

  public function closeModal(): void {
    $this->show = false;
  }

  public function toggleTagSelection(int $tagId): void {
    if (in_array($tagId, $this->selectedTagIds)) {
      $this->selectedTagIds = array_values(array_diff($this->selectedTagIds, [$tagId]));
    } else {
      $this->selectedTagIds[] = $tagId;
    }
  }

  public function render() {
    $categories = $this->list ? $this->list->categories : collect();
    $tags       = $this->list ? $this->list->tags : collect();
    return view('livewire.item-edit-modal', compact('categories', 'tags'));
  }
}
