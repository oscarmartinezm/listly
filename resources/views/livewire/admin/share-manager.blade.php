<div class="bg-white rounded-xl shadow-sm mb-4">
  <div class="px-4 py-3 border-b">
    <h3 class="text-sm font-semibold text-gray-600">Compartir <span class="font-normal text-gray-400">— {{ $listName }}</span></h3>
  </div>

  @if($isOwner)
    {{-- Share Link --}}
    <div class="px-4 py-3 border-b">
      <label class="text-xs text-gray-500 mb-1 block">Link para compartir</label>
      <div class="flex gap-2" x-data="{ copied: false }">
        <input type="text" value="{{ $shareUrl }}" readonly
               class="flex-1 text-xs border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-600">
        <button @click="navigator.clipboard.writeText('{{ $shareUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                class="text-xs px-3 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition"
                x-text="copied ? 'Copiado!' : 'Copiar'">
          Copiar
        </button>
        <button wire:click="regenerateToken" wire:confirm="Regenerar el link? El link anterior dejara de funcionar."
                class="text-xs px-3 py-2 rounded-lg border border-gray-300 text-amber-600 hover:bg-amber-50 transition">
          Regenerar
        </button>
      </div>
    </div>

    {{-- Shared Users --}}
    <div class="divide-y">
      @forelse($list->sharedUsers as $user)
        <div class="px-4 py-2.5 flex items-center justify-between">
          <div class="flex items-center gap-2">
            @if($user->avatar)
              <img src="{{ $user->avatar }}" class="w-6 h-6 rounded-full" alt="">
            @endif
            <span class="text-sm text-gray-800">{{ $user->name }}</span>
            <span class="text-xs text-gray-400">{{ $user->email }}</span>
          </div>
          <button wire:click="removeUser({{ $user->id }})" wire:confirm="Quitar acceso a {{ $user->name }}?"
                  class="text-xs text-red-400 hover:text-red-600">Quitar</button>
        </div>
      @empty
        <div class="px-4 py-3 text-sm text-gray-400 text-center">
          No has compartido esta lista con nadie
        </div>
      @endforelse
    </div>
  @else
    <div class="px-4 py-3 text-sm text-gray-400 text-center">
      Solo el dueño puede gestionar el acceso compartido
    </div>
  @endif
</div>
