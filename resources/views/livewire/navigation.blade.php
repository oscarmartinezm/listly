<div x-data="{ menuOpen: false }">
  <div class="flex items-center gap-2">
    {{-- Nombre de lista activa --}}
    <a href="{{ route('home') }}" id="active-list-name" class="text-sm px-2 sm:px-3 py-2 rounded-lg truncate max-w-[8rem] sm:max-w-[10rem] {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
      {{ $primaryListName }}
    </a>

    {{-- Botón menú hamburguesa --}}
    <button @click="menuOpen = true" class="text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

  {{-- Panel lateral --}}
  <div x-show="menuOpen"
       x-cloak
       @click.self="menuOpen = false"
       class="fixed inset-0 z-50 bg-black/50"
       x-transition:enter="transition-opacity ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition-opacity ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0">

    <div @click.stop
         x-show="menuOpen"
         x-transition:enter="transition ease-out duration-200 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-150 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white dark:bg-gray-800 shadow-xl overflow-y-auto">

      {{-- Header del menú --}}
      <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Menú</h2>
        <button @click="menuOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      {{-- Mis listas --}}
      @if($ownedLists->count())
      <div class="border-b dark:border-gray-700">
        <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
          Mis listas
        </div>
        @foreach($ownedLists as $list)
        <button @click="window.location.href = '{{ route('admin') }}?setList={{ $list->id }}'"
                type="button"
                data-list-item
                data-list-id="{{ $list->id }}"
                class="w-full text-left px-4 py-3 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-100 flex items-center justify-between {{ $list->id === $primaryListId ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
          <span class="truncate">{{ $list->name }}</span>
          @if($list->id === $primaryListId)
          <svg class="w-4 h-4 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          @endif
        </button>
        @endforeach
      </div>
      @endif

      {{-- Listas compartidas --}}
      @if($sharedLists->count())
      <div class="border-b dark:border-gray-700">
        <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
          Compartidas
        </div>
        @foreach($sharedLists as $list)
        <button @click="window.location.href = '{{ route('admin') }}?setList={{ $list->id }}'"
                type="button"
                data-list-item
                data-list-id="{{ $list->id }}"
                class="w-full text-left px-4 py-3 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-100 flex items-center justify-between {{ $list->id === $primaryListId ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
          <span class="truncate">{{ $list->name }}</span>
          @if($list->id === $primaryListId)
          <svg class="w-4 h-4 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          @endif
        </button>
        @endforeach
      </div>
      @endif

      {{-- Opciones --}}
      <div class="border-b dark:border-gray-700">
        {{-- Ajustes --}}
        <a href="{{ route('admin') }}"
           @click="menuOpen = false"
           class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span>Ajustes</span>
        </a>

        {{-- Dark mode toggle --}}
        <button onclick="toggleDarkMode()"
                class="w-full flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
          <svg class="w-5 h-5" data-icon-sun fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
          <svg class="w-5 h-5" data-icon-moon fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
          </svg>
          <span>Cambiar tema</span>
        </button>
      </div>

      {{-- Salir --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Salir</span>
        </button>
      </form>
    </div>
  </div>
</div>
