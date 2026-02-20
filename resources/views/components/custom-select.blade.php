@props(['wireModel', 'options', 'placeholder' => 'Seleccionar...', 'size' => 'base'])

@php
$sizeClasses = [
    'sm' => 'text-sm px-3 py-2',
    'base' => 'text-base px-3 py-2.5',
];
$textSize = $sizeClasses[$size] ?? $sizeClasses['base'];
$uniqueId = 'select_' . uniqid();
@endphp

<div x-data="{
    id: '{{ $uniqueId }}',
    open: false,
    selected: @entangle($wireModel).live,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
    select(value) {
        this.selected = String(value);
        this.close();
    },
    getLabel() {
        if (!this.selected || this.selected === '') return '{{ $placeholder }}';
        const opts = @js($options);
        const option = opts.find(opt => String(opt.value) === String(this.selected));
        return option ? option.label : '{{ $placeholder }}';
    }
}"
    @click.outside="close()"
    @keydown.escape.window="close()"
    class="relative">

    {{-- Trigger --}}
    <button type="button"
            @click="toggle()"
            class="w-full {{ $textSize }} border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-left flex items-center justify-between">
        <span x-text="getLabel()" class="truncate"></span>
        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 ml-2 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-cloak
         class="absolute z-[100] w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-auto">
        @foreach($options as $option)
        <button type="button"
                @click="select('{{ $option['value'] }}')"
                class="w-full text-left {{ $textSize }} hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-100 transition block"
                :class="{ 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': String(selected) === '{{ $option['value'] }}' }">
            {{ $option['label'] }}
        </button>
        @endforeach
    </div>
</div>
