<?php
namespace App\Livewire;

use App\Models\ShoppingList;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShoppingListView extends Component {
  public ?ShoppingList $list = null;
  public array $activeTagIds = [];
  public bool $showChecked   = true;

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
      'item-changed'       => 'refreshList',
      'item-created'       => 'refreshList',
      'item-deleted'       => 'refreshList',
      'list-updated'       => 'refreshList',
    ];

    if ($this->list) {
      $channel                             = "echo:shopping-list.{$this->list->id}";
      $listeners["{$channel},ItemCreated"] = 'refreshList';
      $listeners["{$channel},ItemUpdated"] = 'refreshList';
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

  public function onTagFilterChanged(array $activeTagIds): void {
    $this->activeTagIds = $activeTagIds;
  }

  public function refreshList(): void {
    if ($this->list) {
      $this->list = $this->list->fresh();
    }
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

      if (! $this->showChecked) {
        $categories->each(function ($category) {
          $category->setRelation('items', $category->items->where('is_checked', false));
        });
      }
    }

    return view('livewire.shopping-list-view', [
      'categories' => $categories,
    ]);
  }
}
