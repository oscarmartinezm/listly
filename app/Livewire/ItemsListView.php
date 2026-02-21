<?php
namespace App\Livewire;

use App\Models\ItemsList;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItemsListView extends Component {
  public ?ItemsList $list = null;
  public array $activeTagIds = [];
  public string $searchText = '';
  public bool $showChecked   = true;
  public bool $showUnchecked = true;
  public bool $showFilters = false;
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
      'filters-changed'    => 'onFiltersChanged',
      'item-created'       => 'refreshList',
      'item-updated'       => 'refreshList',
      'item-deleted'       => 'refreshList',
      'list-updated'       => 'refreshList',
      'list-changed'       => 'reloadPage',
    ];

    if ($this->list) {
      $channel                             = "echo:listly.{$this->list->id}";
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

  public function toggleShowUnchecked(): void {
    $this->showUnchecked = ! $this->showUnchecked;
  }

  public function onTagFilterChanged(array $activeTagIds): void {
    $this->activeTagIds = $activeTagIds;
  }

  public function onFiltersChanged(array $filters): void {
    $this->searchText = $filters['searchText'] ?? '';
    $this->activeTagIds = $filters['activeTagIds'] ?? [];
  }

  public function toggleFilters(): void {
    $this->showFilters = ! $this->showFilters;
  }

  public function toggleFilterTag(int $tagId): void {
    if (in_array($tagId, $this->activeTagIds)) {
      $this->activeTagIds = array_values(array_diff($this->activeTagIds, [$tagId]));
    } else {
      $this->activeTagIds[] = $tagId;
    }
  }

  public function clearFilters(): void {
    $this->searchText = '';
    $this->activeTagIds = [];
    $this->js('document.querySelector(\'input[wire\\\\:model\\\\.live\\\\.debounce\\\\.300ms="searchText"]\').value = ""');
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
    $hasCategories = false;
    $hasUncategorizedItems = false;

    if ($this->list) {
      $categories = $this->list->categories()->with(['items' => function ($query) {
        $query->with('tags')->orderBy('is_checked')->orderByRaw('LOWER(text)');
      }])->get();

      $hasCategories = $categories->isNotEmpty();

      // Verificar si hay items sin categoría
      $hasUncategorizedItems = $this->list->items()->whereNull('category_id')->exists();

      // Aplicar filtros de búsqueda y tags
      $categories->each(function ($category) {
        $filteredItems = $category->items;

        // Filtro por tags
        if (! empty($this->activeTagIds)) {
          $filteredItems = $filteredItems->filter(function ($item) {
            return $item->tags->whereIn('id', $this->activeTagIds)->isNotEmpty();
          });
        }

        // Filtro por texto (mínimo 3 caracteres)
        if (strlen($this->searchText) >= 3) {
          $search = strtolower($this->searchText);
          $filteredItems = $filteredItems->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->text), $search);
          });
        }

        $category->setRelation('items', $filteredItems);
      });

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
      'hasCategories' => $hasCategories,
      'hasUncategorizedItems' => $hasUncategorizedItems,
    ]);
  }
}
