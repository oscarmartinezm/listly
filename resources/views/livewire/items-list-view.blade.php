<div>
  @if($list)
  <div class="mb-4">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $list->name }}</h2>
  </div>

  {{-- Tag Filter --}}
  @if($list->tags->count())
    <livewire:tag-filter :list="$list" :activeTagIds="$activeTagIds" :key="'tag-filter-' . $list->id" />
  @endif

  {{-- Filter buttons --}}
  <div class="mb-3 flex items-center gap-2">
    <button wire:click="toggleShowUnchecked" class="text-sm px-3.5 py-2 rounded-full border transition {{ $showUnchecked ? 'bg-blue-100 text-blue-700 border-blue-300 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-700' : 'bg-white text-gray-400 border-gray-200 dark:bg-gray-800 dark:text-gray-500 dark:border-gray-700' }}">
      Pendientes
    </button>
    <button wire:click="toggleShowChecked" class="text-sm px-3.5 py-2 rounded-full border transition {{ $showChecked ? 'bg-green-100 text-green-700 border-green-300 dark:bg-green-900/50 dark:text-green-300 dark:border-green-700' : 'bg-white text-gray-400 border-gray-200 dark:bg-gray-800 dark:text-gray-500 dark:border-gray-700' }}">
      Marcados
    </button>
  </div>

  {{-- Categories --}}
  @foreach($categories as $category)
    @if($category->items->count() || empty($activeTagIds))
    <livewire:category-section
      :category="$category"
      :showChecked="$showChecked"
      :showUnchecked="$showUnchecked"
      :key="'cat-' . $category->id . '-' . $refreshKey" />
    @endif
  @endforeach

  @if($categories->isEmpty())
    <div class="text-center py-12 text-gray-400 dark:text-gray-500">
    <p class="text-lg">No hay categorias</p>
    <p class="text-sm mt-1">Ve a <a href="{{ route('admin') }}" class="text-blue-500 underline">Ajustes</a> para crear una</p>
    </div>
  @endif
  @else
  <div class="text-center py-12 text-gray-400 dark:text-gray-500">
    <p class="text-lg">No tienes listas</p>
    <p class="text-sm mt-1">Ve a <a href="{{ route('admin') }}" class="text-blue-500 underline">Ajustes</a> para crear una</p>
  </div>
  @endif
</div>
