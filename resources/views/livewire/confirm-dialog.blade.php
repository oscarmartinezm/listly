<div>
  @if($show)
  <div class="fixed inset-0 z-50 overflow-y-auto">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="cancel"></div>

    {{-- Modal --}}
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full" @click.stop>
        {{-- Icon --}}
        <div class="p-6 pb-4">
          <div class="flex items-center justify-center w-12 h-12 mx-auto bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
        </div>

        {{-- Content --}}
        <div class="px-6 pb-4">
          @if($title)
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 text-center mb-2">
            {{ $title }}
          </h3>
          @endif
          @if($message)
          <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            {{ $message }}
          </p>
          @endif
        </div>

        {{-- Actions --}}
        <div class="px-6 pb-6 flex gap-3">
          <button wire:click="cancel"
                  class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            {{ $cancelText }}
          </button>
          <button wire:click="confirm"
                  class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
            {{ $confirmText }}
          </button>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
