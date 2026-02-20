<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-4">
  <div class="px-4 py-3 border-b dark:border-gray-700">
  <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300">Tags <span class="font-normal text-gray-400 dark:text-gray-500">— {{ $listName }}</span></h3>
  </div>

  {{-- Create --}}
  <div class="px-4 py-3 border-b dark:border-gray-700">
  <form wire:submit="create" class="flex gap-2 items-center">
    <input type="text" wire:model="newName" placeholder="Nuevo tag..."
       class="flex-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
    <input type="color" wire:model="newColor" class="w-8 h-8 rounded cursor-pointer border-0">
    <button type="submit" class="bg-blue-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-600">+</button>
  </form>
  </div>

  {{-- List --}}
  <div class="divide-y dark:divide-gray-700">
  @forelse($tags as $tag)
    <div class="px-4 py-2.5 flex items-center justify-between">
    @if($editingId === $tag->id)
      <form wire:submit="saveEdit" class="flex gap-2 items-center flex-1">
      <input type="text" wire:model="editName"
           class="flex-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
           autofocus>
      <input type="color" wire:model="editColor" class="w-8 h-8 rounded cursor-pointer border-0">
      <button type="submit" class="text-xs text-blue-500">OK</button>
      <button type="button" wire:click="cancelEdit" class="text-xs text-gray-400">X</button>
      </form>
    @else
      <div class="flex items-center gap-2">
      <span class="w-3 h-3 rounded-full" style="background-color: {{ $tag->color }}"></span>
      <span class="text-sm text-gray-800 dark:text-gray-100">{{ $tag->name }}</span>
      </div>
      <div class="flex items-center gap-2">
      <button wire:click="startEdit({{ $tag->id }})" class="text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">Editar</button>
      <button wire:click="confirmDelete({{ $tag->id }})"
          class="text-sm text-red-400 hover:text-red-600">Eliminar</button>
      </div>
    @endif
    </div>
  @empty
    <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">Sin tags</div>
  @endforelse
  </div>
</div>
