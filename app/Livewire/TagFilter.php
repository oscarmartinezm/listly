<?php
namespace App\Livewire;

use App\Models\ItemsList;
use Livewire\Component;

class TagFilter extends Component {
  public ItemsList $list;
  public array $activeTagIds = [];

  public function toggle(int $tagId): void {
    if (in_array($tagId, $this->activeTagIds)) {
      $this->activeTagIds = array_values(array_diff($this->activeTagIds, [$tagId]));
    } else {
      $this->activeTagIds[] = $tagId;
    }
    $this->dispatch('tag-filter-changed', activeTagIds: $this->activeTagIds);
  }

  public function render() {
    return view('livewire.tag-filter', [
      'tags' => $this->list->tags,
    ]);
  }
}
