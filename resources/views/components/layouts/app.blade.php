<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, interactive-widget=resizes-visual">
  <script>
    (function() {
      var s = localStorage.getItem('theme');
      if (s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      }
    })();

  </script>
  <style>
    .dark [data-icon-moon] {
      display: none !important;
    }

    html:not(.dark) [data-icon-sun] {
      display: none !important;
    }

  </style>
  @include('partials.pwa-head')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-dvh bg-gray-100 dark:bg-gray-900">
  {{-- Navigation --}}
  <nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50">
    <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-2">
        <img src="/logo.png" alt="Listly" class="w-7 h-7 rounded-lg">
        <span class="font-bold text-lg text-gray-800 dark:text-gray-100">Listly</span>
      </a>
      <livewire:navigation />
    </div>
  </nav>

  {{-- Content --}}
  <main class="max-w-2xl mx-auto px-4 py-4">
    {{ $slot }}
  </main>

  @livewireScripts
  <script>
    function toggleItemCheck(btn) {
      var row = btn.closest('.item-row');
      row.classList.toggle('bg-gray-50');
      row.classList.toggle('dark:bg-gray-700/50');
      var box = btn.querySelector('[data-box]');
      box.classList.toggle('bg-green-500');
      box.classList.toggle('border-green-500');
      box.classList.toggle('border-gray-300');
      box.classList.toggle('dark:border-gray-600');
      box.querySelector('svg').classList.toggle('hidden');
      var text = row.querySelector('[data-text]');
      if (text) {
        text.classList.toggle('line-through');
        text.classList.toggle('text-gray-400');
        text.classList.toggle('text-gray-800');
        text.classList.toggle('dark:text-gray-100');
      }
    }

    function toggleDarkMode() {
      document.documentElement.classList.toggle('dark');
      localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    }

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
      if (!localStorage.getItem('theme')) {
        document.documentElement.classList.toggle('dark', e.matches);
      }
    });

    function filterCategoryItems(input, containerId) {
      const filterText = input.value.toLowerCase();
      const container = document.querySelector('[data-items-container="' + containerId + '"]');
      if (!container) return;

      const items = container.querySelectorAll('[data-item-text]');
      items.forEach(item => {
        const itemText = item.getAttribute('data-item-text');
        if (filterText.length < 2 || itemText.includes(filterText)) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    }

  </script>
  @include('partials.pwa-sw')
</body>
</html>
