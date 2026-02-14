<div>
  <h2 class="text-xl font-bold text-gray-800 mb-4">Administrar</h2>

  {{-- Create List --}}
  <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
  <h3 class="text-sm font-semibold text-gray-600 mb-3">Crear lista</h3>
  <form wire:submit="createList" class="flex gap-2">
    <input type="text" wire:model="newListName" id="input-new-list" placeholder="Nombre de la lista..."
       class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
    <button type="submit" class="bg-blue-500 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-600">
    Crear
    </button>
  </form>
  </div>

  {{-- My Lists --}}
  <div class="bg-white rounded-xl shadow-sm mb-4">
  <div class="px-4 py-3 border-b">
    <h3 class="text-sm font-semibold text-gray-600">Mis listas</h3>
  </div>
  <div class="divide-y">
    @forelse($ownedLists as $list)
    <div class="px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-2 flex-1 min-w-0">
      @if($editingListId === $list->id)
        <form wire:submit="saveEdit" class="flex gap-2 flex-1">
        <input type="text" wire:model="editListName"
             class="flex-1 text-sm border border-gray-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
             autofocus>
        <button type="submit" class="text-xs text-blue-500">OK</button>
        <button type="button" wire:click="cancelEdit" class="text-xs text-gray-400">X</button>
        </form>
      @else
        <span class="text-sm text-gray-800 truncate">{{ $list->name }}</span>
        @if($activeListId === $list->id)
        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full flex-shrink-0">Activa</span>
        @endif
      @endif
      </div>
      @if($editingListId !== $list->id)
      <div class="flex items-center gap-2 flex-shrink-0 ml-2">
        @if($activeListId !== $list->id)
        <button wire:click="setPrimary({{ $list->id }})" class="text-xs text-blue-500 hover:underline">Activar</button>
        @endif
        <button wire:click="startEdit({{ $list->id }})" class="text-xs text-gray-400 hover:text-gray-600">Editar</button>
        <button wire:click="deleteList({{ $list->id }})" wire:confirm="Eliminar esta lista y todos sus items?"
            class="text-xs text-red-400 hover:text-red-600">Eliminar</button>
      </div>
      @endif
    </div>
    @empty
    <div class="px-4 py-3 text-sm text-gray-400 text-center">No tienes listas</div>
    @endforelse
  </div>
  </div>

  {{-- Shared Lists --}}
  @if($sharedLists->count())
  <div class="bg-white rounded-xl shadow-sm mb-4">
    <div class="px-4 py-3 border-b">
    <h3 class="text-sm font-semibold text-gray-600">Listas compartidas conmigo</h3>
    </div>
    <div class="divide-y">
    @foreach($sharedLists as $list)
      <div class="px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="text-sm text-gray-800">{{ $list->name }}</span>
        <span class="text-xs text-gray-400">por {{ $list->owner->name }}</span>
        @if($activeListId === $list->id)
        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Activa</span>
        @endif
      </div>
      @if($activeListId !== $list->id)
        <button wire:click="setPrimary({{ $list->id }})" class="text-xs text-blue-500 hover:underline">Activar</button>
      @endif
      </div>
    @endforeach
    </div>
  </div>
  @endif

  {{-- Admin sections for active list --}}
  @if($activeListId)
  <livewire:admin.category-manager :listId="$activeListId" :key="'cat-mgr-' . $activeListId" />
  <livewire:admin.tag-manager :listId="$activeListId" :key="'tag-mgr-' . $activeListId" />
  <livewire:admin.share-manager :listId="$activeListId" :key="'share-mgr-' . $activeListId" />
  <livewire:admin.bulk-item-import :listId="$activeListId" :key="'bulk-' . $activeListId" />
  @endif
</div>
