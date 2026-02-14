<div class="mb-3 flex flex-wrap gap-1.5">
  @foreach($tags as $tag)
  <button wire:click="toggle({{ $tag->id }})"
      class="text-xs px-2.5 py-1 rounded-full border transition
        {{ in_array($tag->id, $activeTagIds) ? 'text-white border-transparent' : 'text-gray-600 border-gray-300 bg-white' }}"
      style="{{ in_array($tag->id, $activeTagIds) ? 'background-color: ' . $tag->color : '' }}">
    {{ $tag->name }}
  </button>
  @endforeach
</div>
