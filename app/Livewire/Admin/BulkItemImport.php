<?php
namespace App\Livewire\Admin;

use App\Models\ItemsList;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BulkItemImport extends Component {
  public int $listId;
  public string $itemsText     = '';
  public ?int $categoryId      = null;
  public string $resultMessage = '';
  public int $addedCount       = 0;
  public int $skippedCount     = 0;

  public function import(): void {
    $list = ItemsList::findOrFail($this->listId);
    if (! $list->userHasAccess(Auth::user())) {
      return;
    }

    $lines = array_filter(
      array_map('trim', explode("\n", $this->itemsText)),
      fn($line) => $line !== ''
    );

    if (empty($lines)) {
      return;
    }

    $existingItems = $list->items()
      ->pluck('text')
      ->map(fn($t) => mb_strtolower($t))
      ->toArray();

    $maxOrder = $list->items()->max('order') ?? 0;
    $added    = 0;
    $skipped  = 0;

    foreach ($lines as $line) {
      if (in_array(mb_strtolower($line), $existingItems)) {
        $skipped++;
        continue;
      }

      $maxOrder++;
      $list->items()->create([
        'text'        => $line,
        'category_id' => $this->categoryId ?: null,
        'order'       => $maxOrder,
      ]);
      $existingItems[] = mb_strtolower($line);
      $added++;
    }

    $this->addedCount    = $added;
    $this->skippedCount  = $skipped;
    $this->resultMessage = "Se agregaron {$added} items" .
      ($skipped > 0 ? " ({$skipped} duplicados omitidos)" : '');
    $this->itemsText = '';
  }

  public function render() {
    $list       = ItemsList::findOrFail($this->listId);
    $categories = $list->categories;
    $listName   = $list->name;
    return view('livewire.admin.bulk-item-import', compact('categories', 'listName'));
  }
}
