<div class="mb-4">
  <form wire:submit="addItem" class="bg-white rounded-xl shadow-sm p-3">
    <div class="flex gap-2">
      <input type="text"
             wire:model.live.debounce.300ms="text"
             placeholder="Agregar item..."
             class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      <select wire:model="categoryId"
              class="text-sm border border-gray-300 rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
        <option value="">Sin categoria</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
      <button type="submit"
              class="bg-blue-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-600 transition flex-shrink-0">
        +
      </button>
    </div>
    @if($duplicateWarning)
      <p class="text-xs text-amber-600 mt-1.5 px-1">{{ $duplicateWarning }}</p>
    @endif
  </form>
</div>
