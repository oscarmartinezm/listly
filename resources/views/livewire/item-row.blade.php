<div class="item-row px-4 py-3.5 flex items-center gap-3 group {{ $item->is_checked ? 'bg-gray-50 dark:bg-gray-700/50' : '' }}">
  {{-- Checkbox --}}
  <button wire:click="toggleCheck" onclick="toggleItemCheck(this)" class="flex-shrink-0">
    <div data-box class="w-6 h-6 rounded border-2 flex items-center justify-center transition
      {{ $item->is_checked ? 'bg-green-500 border-green-500' : 'border-gray-300 dark:border-gray-600 hover:border-blue-400' }}">
      <svg class="w-3.5 h-3.5 text-white {{ $item->is_checked ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
      </svg>
    </div>
  </button>

  {{-- Text --}}
  <div class="flex-1 min-w-0">
  @if($editing)
    <form wire:submit="saveEdit" class="flex gap-2">
    <input type="text" wire:model="editText"
         class="flex-1 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
         autofocus
         wire:keydown.escape="cancelEdit">
    <button type="submit" class="text-sm text-blue-500">OK</button>
    <button type="button" wire:click="cancelEdit" class="text-sm text-gray-400">X</button>
    </form>
  @else
    <div class="flex items-center gap-2">
    <span data-text class="text-base {{ $item->is_checked ? 'line-through text-gray-400' : 'text-gray-800 dark:text-gray-100' }} cursor-pointer" wire:click="startEdit">
      {{ $item->text }}
    </span>
    {{-- Tags --}}
    @if($item->tags->count())
    <span class="flex gap-1 mt-0.5">
      @foreach($item->tags as $tag)
      <span class="text-xs px-1.5 py-0.5 rounded-full text-white"
          style="background-color: {{ $tag->color }}">
        {{ $tag->name }}
      </span>
      @endforeach
    </span>
    @endif
    @if($item->link)
      <a href="{{ $item->link }}" target="_blank" class="text-blue-400 hover:text-blue-600 flex-shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
      </svg>
      </a>
    @endif
    </div>
  @endif
  </div>

  {{-- Actions --}}
  @unless($editing)
  <div class="flex items-center gap-2">
    <button wire:click="$dispatch('open-edit-modal', { itemId: {{ $item->id }} })"
        class="text-gray-400 hover:text-blue-500 p-1.5">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>
    </button>
    <button wire:click="delete" wire:confirm="Eliminar este item?"
        class="text-gray-400 hover:text-red-500 p-1.5">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
    </svg>
    </button>
  </div>
  @endunless
</div>
