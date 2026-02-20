<?php
namespace App\Livewire;

use App\Models\ItemsList;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItemsListView extends Component {
  public ?ItemsList $list = null;
  public array $activeTagIds = [];
  public bool $showChecked   = true;
  public bool $showUnchecked = true;
  public int $refreshKey = 0;

  public function mount(): void {
    $user       = Auth::user();
    $this->list = $user->primaryList;

    if (! $this->list) {
      $first = $user->ownedLists()->first() ?? $user->sharedLists()->first();
      if ($first) {
        $user->update(['primary_list_id' => $first->id]);
        $this->list = $first;
      }
    }
  }

  public function getListeners(): array {
    $listeners = [
      'tag-filter-changed' => 'onTagFilterChanged',
      'item-created'       => 'refreshList',
      'item-updated'       => 'refreshList',
      'item-deleted'       => 'refreshList',
      'list-updated'       => 'refreshList',
      'list-changed'       => 'reloadPage',
    ];

    if ($this->list) {
      $channel                             = "echo:listly.{$this->list->id}";
      $listeners["{$channel},ItemCreated"] = 'refreshList';
      $listeners["{$channel},ItemDeleted"] = 'refreshList';
      $listeners["{$channel},ListUpdated"] = 'refreshList';
    }

    return $listeners;
  }

  public function toggleTag(int $tagId): void {
    if (in_array($tagId, $this->activeTagIds)) {
      $this->activeTagIds = array_values(array_diff($this->activeTagIds, [$tagId]));
    } else {
      $this->activeTagIds[] = $tagId;
    }
  }

  public function toggleShowChecked(): void {
    $this->showChecked = ! $this->showChecked;
  }

  public function toggleShowUnchecked(): void {
    $this->showUnchecked = ! $this->showUnchecked;
  }

  public function onTagFilterChanged(array $activeTagIds): void {
    $this->activeTagIds = $activeTagIds;
  }

  public function refreshList(): void {
    if ($this->list) {
      $this->list = $this->list->fresh();
      $this->refreshKey++;
    }
  }

  public function reloadPage(): void {
    $this->redirect(route('home'), navigate: true);
  }

  public function render() {
    $categories = collect();

    if ($this->list) {
      $categories = $this->list->categories()->with(['items' => function ($query) {
        $query->with('tags')->orderBy('is_checked')->orderByRaw('LOWER(text)');
      }])->get();

      if (! empty($this->activeTagIds)) {
        $categories->each(function ($category) {
          $category->setRelation('items', $category->items->filter(function ($item) {
            return $item->tags->whereIn('id', $this->activeTagIds)->isNotEmpty();
          }));
        });
      }

      if (! $this->showChecked || ! $this->showUnchecked) {
        $categories->each(function ($category) {
          $category->setRelation('items', $category->items->filter(function ($item) {
            if ($item->is_checked && ! $this->showChecked) return false;
            if (! $item->is_checked && ! $this->showUnchecked) return false;
            return true;
          }));
        });
      }
    }

    return view('livewire.items-list-view', [
      'categories' => $categories,
    ]);
  }
}
