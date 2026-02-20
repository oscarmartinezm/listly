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
