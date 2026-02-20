<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-4">
  <div class="px-4 py-3 border-b dark:border-gray-700">
  <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300">Categorias <span class="font-normal text-gray-400 dark:text-gray-500">— {{ $listName }}</span></h3>
  </div>

  {{-- Create --}}
  <div class="px-4 py-3 border-b dark:border-gray-700">
  <form wire:submit="create" class="flex gap-2">
    <input type="text" wire:model="newName" id="input-new-category" placeholder="Nueva categoria..."
       class="flex-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
    <button type="submit" class="bg-blue-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-600">+</button>
  </form>
  </div>

  {{-- List --}}
  <div class="divide-y dark:divide-gray-700">
  @forelse($categories as $cat)
    <div class="px-4 py-2.5 flex items-center justify-between">
    @if($editingId === $cat->id)
      <form wire:submit="saveEdit" class="flex gap-2 flex-1">
      <input type="text" wire:model="editName"
           class="flex-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
           autofocus>
      <button type="submit" class="text-xs text-blue-500">OK</button>
      <button type="button" wire:click="cancelEdit" class="text-xs text-gray-400">X</button>
      </form>
    @else
      <span class="text-sm text-gray-800 dark:text-gray-100">{{ $cat->name }}</span>
      <div class="flex items-center gap-2">
      <button wire:click="moveUp({{ $cat->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-xs">&#9650;</button>
      <button wire:click="moveDown({{ $cat->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-xs">&#9660;</button>
      <button wire:click="startEdit({{ $cat->id }})" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">Editar</button>
      <button wire:click="delete({{ $cat->id }})" wire:confirm="Eliminar esta categoria? Los items quedaran sin categoria."
          class="text-xs text-red-400 hover:text-red-600">Eliminar</button>
      </div>
    @endif
    </div>
  @empty
    <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">Sin categorias</div>
  @endforelse
  </div>
</div>
