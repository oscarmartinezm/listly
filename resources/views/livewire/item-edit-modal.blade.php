<div>
  @if($show)
  <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center"
     x-data x-on:keydown.escape.window="$wire.closeModal()">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40" wire:click="closeModal"></div>

    {{-- Modal --}}
    <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-5 max-h-[85vh] overflow-y-auto">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Editar item</h3>

    <div class="space-y-4">
      {{-- Text --}}
      <div>
      <label class="text-sm text-gray-600 mb-1 block">Nombre</label>
      <input type="text" wire:model="text"
           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      {{-- Category --}}
      <div>
      <label class="text-sm text-gray-600 mb-1 block">Categoria</label>
      <select wire:model="categoryId"
          class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
        <option value="">Sin categoria</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
      </div>

      {{-- Link --}}
      <div>
      <label class="text-sm text-gray-600 mb-1 block">Link (opcional)</label>
      <input type="url" wire:model="link" placeholder="https://..."
           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      {{-- Tags --}}
      @if($tags->count())
      <div>
        <label class="text-sm text-gray-600 mb-1 block">Tags</label>
        <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
          <button type="button" wire:click="toggleTagSelection({{ $tag->id }})"
              class="text-xs px-2.5 py-1 rounded-full border transition
              {{ in_array($tag->id, $selectedTagIds) ? 'text-white border-transparent' : 'text-gray-600 border-gray-300 bg-white' }}"
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
          class="text-sm px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
      Cancelar
      </button>
      <button wire:click="save"
          class="text-sm px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600">
      Guardar
      </button>
    </div>
    </div>
  </div>
  @endif
</div>
