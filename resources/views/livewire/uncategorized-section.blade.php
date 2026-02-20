<div class="mb-4">
  @if($showCollapsible)
  {{-- With collapsible --}}
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    {{-- Header --}}
    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700 flex items-center justify-between cursor-pointer"
       onclick="let c=this.parentElement.querySelector('[data-content]'); c.hidden=!c.hidden; this.querySelector('[data-arrow]').classList.toggle('rotate-90')">
      <div class="flex items-center gap-2">
        <svg data-arrow class="w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform rotate-90"
           fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-base font-semibold text-gray-600 dark:text-gray-300">Sin Categoría</span>
        <span class="text-xs text-gray-400 dark:text-gray-500">({{ $items->count() }})</span>
      </div>
      <button wire:click.stop="uncheckAll" class="text-sm text-blue-500 hover:text-blue-700">Desmarcar</button>
    </div>

    {{-- Content --}}
    <div data-content>
      {{-- Add Item --}}
      <div class="px-4 py-2.5 border-b dark:border-gray-700">
        <form wire:submit="addItem" class="flex gap-2">
          <input type="text" wire:model="newItemText" id="input-add-uncategorized" placeholder="Agregar item..."
             class="flex-1 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <button type="submit" class="bg-blue-500 text-white text-base px-4 py-2 rounded-lg hover:bg-blue-600 transition">+</button>
        </form>
      </div>

      {{-- Items --}}
      <div class="divide-y dark:divide-gray-700">
        @foreach($items as $item)
          <livewire:item-row :item="$item" :key="'item-' . $item->id . '-v' . $version" />
        @endforeach
        @if($items->isEmpty())
          <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">Sin items</div>
        @endif
      </div>
    </div>
  </div>
  @else
  {{-- Without collapsible --}}
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    {{-- Header without collapsible --}}
    <div class="px-4 py-2.5 border-b dark:border-gray-700 flex justify-end">
      <button wire:click="uncheckAll" class="text-sm text-blue-500 hover:text-blue-700">Desmarcar</button>
    </div>

    {{-- Add Item --}}
    <div class="px-4 py-2.5 border-b dark:border-gray-700">
      <form wire:submit="addItem" class="flex gap-2">
        <input type="text" wire:model="newItemText" id="input-add-uncategorized" placeholder="Agregar item..."
           class="flex-1 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="bg-blue-500 text-white text-base px-4 py-2 rounded-lg hover:bg-blue-600 transition">+</button>
      </form>
    </div>

    {{-- Items --}}
    <div class="divide-y dark:divide-gray-700">
      @foreach($items as $item)
        <livewire:item-row :item="$item" :key="'item-' . $item->id . '-v' . $version" />
      @endforeach
      @if($items->isEmpty())
        <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">No hay items</div>
      @endif
    </div>
  </div>
  @endif
</div>
