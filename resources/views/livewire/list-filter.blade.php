<div class="mb-3">
  {{-- Filter toggle button --}}
  <button wire:click="toggleFilters" class="text-sm px-3.5 py-2 rounded-full border transition flex items-center gap-2 {{ $showFilters || $searchText || count($activeTagIds) ? 'bg-purple-100 text-purple-700 border-purple-300 dark:bg-purple-900/50 dark:text-purple-300 dark:border-purple-700' : 'bg-white text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700' }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
    </svg>
    Filtrar
    @if($searchText || count($activeTagIds))
    <span class="text-xs bg-purple-500 text-white rounded-full px-1.5 py-0.5">{{ count($activeTagIds) + ($searchText ? 1 : 0) }}</span>
    @endif
  </button>

  {{-- Filter panel --}}
  @if($showFilters)
  <div class="mt-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 space-y-3">
    {{-- Search input --}}
    <div>
      <label class="text-sm text-gray-600 dark:text-gray-300 mb-1 block">Buscar por nombre</label>
      <input type="text" wire:model.live.debounce.300ms="searchText" placeholder="Escribe al menos 3 caracteres..."
         class="w-full text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400">
    </div>

    {{-- Tags filter --}}
    @if($tags->count())
    <div>
      <label class="text-sm text-gray-600 dark:text-gray-300 mb-2 block">Filtrar por tags</label>
      <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
        <button type="button" wire:click="toggleTag({{ $tag->id }})"
            class="text-sm px-3 py-1.5 rounded-full border transition
            {{ in_array($tag->id, $activeTagIds) ? 'text-white border-transparent' : 'text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700' }}"
            style="{{ in_array($tag->id, $activeTagIds) ? 'background-color: ' . $tag->color : '' }}">
          {{ $tag->name }}
        </button>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Clear button --}}
    @if($searchText || count($activeTagIds))
    <div class="flex justify-end">
      <button wire:click="clearFilters" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
        Limpiar filtros
      </button>
    </div>
    @endif
  </div>
  @endif
</div>
