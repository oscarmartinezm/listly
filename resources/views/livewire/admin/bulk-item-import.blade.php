<div class="bg-white rounded-xl shadow-sm mb-4">
  <div class="px-4 py-3 border-b">
    <h3 class="text-sm font-semibold text-gray-600">Importar items <span class="font-normal text-gray-400">— {{ $listName }}</span></h3>
  </div>

  <div class="px-4 py-3">
    <form wire:submit="import" class="space-y-3">
      <div>
        <label class="text-xs text-gray-500 mb-1 block">Pega tu lista (un item por linea)</label>
        <textarea wire:model="itemsText"
                  rows="6"
                  placeholder="Leche&#10;Pan&#10;Huevos&#10;Arroz"
                  class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-y"></textarea>
      </div>

      <div class="flex gap-2 items-end">
        <div class="flex-1">
          <label class="text-xs text-gray-500 mb-1 block">Categoria (opcional)</label>
          <select wire:model="categoryId"
                  class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            <option value="">Sin categoria</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit"
                class="bg-blue-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-600 transition flex-shrink-0">
          Importar
        </button>
      </div>
    </form>

    @if($resultMessage)
      <div class="mt-3 text-sm px-3 py-2 rounded-lg {{ $skippedCount > 0 ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700' }}">
        {{ $resultMessage }}
      </div>
    @endif
  </div>
</div>
