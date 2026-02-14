<div class="mb-4">
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
  {{-- Header --}}
  <div class="px-4 py-2.5 bg-gray-50 border-b flex items-center justify-between cursor-pointer"
     wire:click="toggleCollapse">
    <div class="flex items-center gap-2">
    <svg class="w-4 h-4 text-gray-400 transition-transform {{ $collapsed ? '' : 'rotate-90' }}"
       fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <span class="text-sm font-semibold text-gray-600">{{ $category->name }}</span>
    <span class="text-xs text-gray-400">({{ $items->count() }})</span>
    </div>
    <div class="flex items-center gap-2">
    <button wire:click.stop="reloadCategory"
        class="text-gray-400 hover:text-blue-500 p-1 rounded transition"
        title="Reordenar">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4.93 9a9 9 0 0115.14 0M19.07 15a9 9 0 01-15.14 0"/>
      </svg>
    </button>
    @if($items->where('is_checked', true)->count())
      <button wire:click.stop="uncheckAll"
          class="text-xs text-blue-500 hover:text-blue-700">
      Desmarcar
      </button>
    @endif
    </div>
  </div>

  {{-- Content --}}
  @unless($collapsed)
    {{-- Add Item --}}
    <div class="px-4 py-2 border-b">
    <form wire:submit="addItem" class="flex gap-2">
      <input type="text" wire:model="newItemText" id="input-add-item-{{ $category->id }}" placeholder="Agregar item..."
         class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-400">
      <button type="submit"
          class="bg-blue-500 text-white text-sm px-3 py-1.5 rounded-lg hover:bg-blue-600 transition">
      +
      </button>
    </form>
    </div>

    {{-- Items --}}
    <div class="divide-y">
    @foreach($items as $item)
      <livewire:item-row :item="$item" :key="'item-' . $item->id . '-v' . $version" />
    @endforeach
    @if($items->isEmpty())
      <div class="px-4 py-3 text-sm text-gray-400 text-center">Sin items</div>
    @endif
    </div>
  @endunless
  </div>
</div>
