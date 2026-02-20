<div>
  @if($show)
  <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center"
     x-data x-on:keydown.escape.window="$wire.closeModal()">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 dark:bg-black/60" wire:click="closeModal"></div>

    {{-- Modal --}}
    <div class="relative bg-white dark:bg-gray-800 w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-5 max-h-[85vh] overflow-y-auto">
    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Editar item</h3>

    <div class="space-y-4">
      {{-- Text --}}
      <div>
      <label class="text-sm text-gray-600 dark:text-gray-300 mb-1 block">Nombre</label>
      <input type="text" wire:model="text"
           class="w-full text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      {{-- Category --}}
      <div>
      <label class="text-sm text-gray-600 dark:text-gray-300 mb-1 block">Categoria</label>
      <x-custom-select
        wire-model="categoryId"
        :options="collect([['value' => '', 'label' => 'Sin categoria']])->merge($categories->map(fn($cat) => ['value' => $cat->id, 'label' => $cat->name]))->toArray()"
        placeholder="Sin categoria"
        size="base" />
      </div>

      {{-- Link --}}
      <div>
      <label class="text-sm text-gray-600 dark:text-gray-300 mb-1 block">Link (opcional)</label>
      <input type="url" wire:model="link" placeholder="https://..." class="w-full text-base border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      {{-- Tags --}}
      @if($tags->count())
      <div>
        <label class="text-sm text-gray-600 dark:text-gray-300 mb-1 block">Tags</label>
        <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
          <button type="button" wire:click="toggleTagSelection({{ $tag->id }})"
              class="text-sm px-3 py-1.5 rounded-full border transition
              {{ in_array($tag->id, $selectedTagIds) ? 'text-white border-transparent' : 'text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700' }}"
              style="{{ in_array($tag->id, $selectedTagIds) ? 'background-color: ' . $tag->color : '' }}">
          {{ $tag->name }}
          </button>
        @endforeach
        </div>
      </div>
      @endif
    </div>

    {{-- Actions --}}
    <div class="mt-6 flex gap-2 justify-end">
      <button wire:click="closeModal"
          class="text-base px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
      Cancelar
      </button>
      <button wire:click="save"
          class="text-base px-4 py-2.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
      Guardar
      </button>
    </div>
    </div>
  </div>
  @endif
</div>
