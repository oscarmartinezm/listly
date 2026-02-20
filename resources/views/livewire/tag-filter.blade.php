<!--
TO-DO: re-enabled filtering by tags
-->
<div class="mb-3 flex flex-wrap gap-1.5">
  @foreach($tags as $tag)
  <!--button wire:click="toggle({{ $tag->id }})" class="text-xs px-2.5 py-1 rounded-full border transition
        {{ in_array($tag->id, $activeTagIds) ? 'text-white border-transparent' : 'text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700' }}"
      style="{{ in_array($tag->id, $activeTagIds) ? 'background-color: ' . $tag->color : '' }}">
    {{ $tag->name }}
  </button-->
  @endforeach
</div>
