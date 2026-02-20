<?php
namespace App\Livewire;

use App\Models\ItemsList;
use Livewire\Component;

class ListFilter extends Component {
  public ItemsList $list;
  public string $searchText = '';
  public array $activeTagIds = [];
  public bool $showFilters = false;

  public function toggleFilters(): void {
    $this->showFilters = ! $this->showFilters;
  }

  public function toggleTag(int $tagId): void {
    if (in_array($tagId, $this->activeTagIds)) {
      $this->activeTagIds = array_values(array_diff($this->activeTagIds, [$tagId]));
    } else {
      $this->activeTagIds[] = $tagId;
    }
    $this->dispatchFilters();
  }

  public function updatedSearchText(): void {
    $this->dispatchFilters();
  }

  public function clearFilters(): void {
    $this->searchText = '';
    $this->activeTagIds = [];
    $this->dispatchFilters();
  }

  private function dispatchFilters(): void {
    $this->dispatch('filters-changed', [
      'searchText' => $this->searchText,
      'activeTagIds' => $this->activeTagIds,
    ]);
  }

  public function render() {
    return view('livewire.list-filter', [
      'tags' => $this->list->tags,
    ]);
  }
}
