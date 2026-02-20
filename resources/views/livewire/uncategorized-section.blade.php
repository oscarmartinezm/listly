<div class="mb-4">
  @if($showCollapsible)
  {{-- With collapsible --}}
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    {{-- Header --}}
    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b dark:border-gray-700 flex items-center justify-between">
      <div class="flex items-center gap-2 cursor-pointer flex-1" onclick="let c=this.closest('.bg-white').querySelector('[data-content]'); let b=this.parentElement.querySelector('[data-uncheck-btn]'); if(c.hidden){c.hidden=false;b.classList.remove('hidden');this.querySelector('svg').classList.add('rotate-90')}else{c.hidden=true;b.classList.add('hidden');this.querySelector('svg').classList.remove('rotate-90')}">
        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform rotate-90"
           fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-base font-semibold text-gray-600 dark:text-gray-300">Sin Categoría</span>
        <span class="text-xs text-gray-400 dark:text-gray-500">({{ $items->count() }})</span>
      </div>
      <button wire:click="confirmUncheckAll" data-uncheck-btn class="text-sm text-blue-500 hover:text-blue-700">Desmarcar Todo</button>
    </div>

    {{-- Content --}}
    <div data-content>
      {{-- Add Item --}}
      <div class="px-4 py-2.5 border-b dark:border-gray-700">
        <form wire:submit="addItem" class="flex gap-2">
          <input type="text" wire:model="newItemText"
                 oninput="filterCategoryItems(this, 'uncat-collapsible')"
                 id="input-add-uncategorized" placeholder="Agregar item..."
             class="flex-1 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <button type="submit" class="bg-blue-500 text-white text-base px-4 py-2 rounded-lg hover:bg-blue-600 transition">+</button>
        </form>
      </div>

      {{-- Items --}}
      <div class="divide-y dark:divide-gray-700" data-items-container="uncat-collapsible">
        @foreach($items as $item)
          <div data-item-text="{{ strtolower($item->text) }}">
            <livewire:item-row :item="$item" :key="'item-' . $item->id . '-v' . $version" />
          </div>
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
      <button wire:click="confirmUncheckAll" class="text-sm text-blue-500 hover:text-blue-700">Desmarcar Todo</button>
    </div>

    {{-- Add Item --}}
    <div class="px-4 py-2.5 border-b dark:border-gray-700">
      <form wire:submit="addItem" class="flex gap-2">
        <input type="text" wire:model="newItemText"
               oninput="filterCategoryItems(this, 'uncat-direct')"
               id="input-add-uncategorized" placeholder="Agregar item..."
           class="flex-1 text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="bg-blue-500 text-white text-base px-4 py-2 rounded-lg hover:bg-blue-600 transition">+</button>
      </form>
    </div>

    {{-- Items --}}
    <div class="divide-y dark:divide-gray-700" data-items-container="uncat-direct">
      @foreach($items as $item)
        <div data-item-text="{{ strtolower($item->text) }}">
          <livewire:item-row :item="$item" :key="'item-' . $item->id . '-v' . $version" />
        </div>
      @endforeach
      @if($items->isEmpty())
        <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">No hay items</div>
      @endif
    </div>
  </div>
  @endif
</div>
