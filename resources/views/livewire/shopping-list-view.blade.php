<div>
  @if($list)
  <div class="mb-4">
    <h2 class="text-xl font-bold text-gray-800">{{ $list->name }}</h2>
  </div>

  {{-- Tag Filter --}}
  @if($list->tags->count())
    <livewire:tag-filter :list="$list" :activeTagIds="$activeTagIds" :key="'tag-filter-' . $list->id" />
  @endif

  {{-- Toggle checked items --}}
  <div class="mb-3 flex items-center gap-2">
    <button wire:click="toggleShowChecked"
        class="text-xs px-3 py-1.5 rounded-full border {{ $showChecked ? 'bg-gray-200 text-gray-700' : 'bg-white text-gray-500' }}">
    {{ $showChecked ? 'Ocultar comprados' : 'Mostrar comprados' }}
    </button>
  </div>

  {{-- Categories --}}
  @foreach($categories as $category)
    @if($category->items->count() || empty($activeTagIds))
    <livewire:category-section
      :category="$category"
      :showChecked="$showChecked"
      :key="'cat-' . $category->id" />
    @endif
  @endforeach

  @if($categories->isEmpty())
    <div class="text-center py-12 text-gray-400">
    <p class="text-lg">No hay categorias</p>
    <p class="text-sm mt-1">Ve a <a href="{{ route('admin') }}" class="text-blue-500 underline">Admin</a> para crear una</p>
    </div>
  @endif
  @else
  <div class="text-center py-12 text-gray-400">
    <p class="text-lg">No tienes listas</p>
    <p class="text-sm mt-1">Ve a <a href="{{ route('admin') }}" class="text-blue-500 underline">Admin</a> para crear una</p>
  </div>
  @endif
</div>
