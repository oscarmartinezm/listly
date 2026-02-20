<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-4">
  <div class="px-4 py-3 border-b dark:border-gray-700">
  <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300">Compartir <span class="font-normal text-gray-400 dark:text-gray-500">— {{ $listName }}</span></h3>
  </div>

  @if($isOwner)
  {{-- Share Link --}}
  <div class="px-4 py-3 border-b dark:border-gray-700">
    <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Link para compartir</label>
    <div class="space-y-2" x-data="{ copied: false }">
    <input type="text" value="{{ $shareUrl }}" readonly
         class="w-full text-xs border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
    <div class="flex gap-2">
      <button @click="navigator.clipboard.writeText('{{ $shareUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
          class="flex-1 text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
          x-text="copied ? 'Copiado!' : 'Copiar'">
        Copiar
      </button>
      <button wire:click="confirmRegenerateToken"
          class="flex-1 text-xs px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition">
        Regenerar
      </button>
    </div>
    </div>
  </div>

  {{-- Shared Users --}}
  <div class="divide-y dark:divide-gray-700">
    @forelse($list->sharedUsers as $user)
    <div class="px-4 py-2.5 flex items-center justify-between">
      <div class="flex items-center gap-2">
      @if($user->avatar)
        <img src="{{ $user->avatar }}" class="w-6 h-6 rounded-full" alt="">
      @endif
      <span class="text-sm text-gray-800 dark:text-gray-100">{{ $user->name }}</span>
      </div>
      <button wire:click="confirmRemoveUser({{ $user->id }}, '{{ $user->name }}')"
          class="text-xs text-red-400 hover:text-red-600">Quitar</button>
    </div>
    @empty
    <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">
      No has compartido esta lista con nadie
    </div>
    @endforelse
  </div>
  @else
  <div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500 text-center">
    Solo el dueño puede gestionar el acceso compartido
  </div>
  @endif
</div>
