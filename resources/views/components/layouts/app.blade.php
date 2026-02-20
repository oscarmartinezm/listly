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
      <div class="flex items-center gap-3">
        <button id="dark-mode-toggle" onclick="toggleDarkMode()" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700" title="Cambiar tema">
          <svg data-icon-sun class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <svg data-icon-moon class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>
        <a href="{{ route('home') }}" class="text-sm px-3 py-2 rounded-lg truncate max-w-[8rem] {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
          {{ Auth::user()->primaryList->name ?? 'Inicio' }}
        </a>
        <a href="{{ route('admin') }}" class="text-sm px-3 py-2 rounded-lg {{ request()->routeIs('admin') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
          Ajustes
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="text-sm px-3 py-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
            Salir
          </button>
        </form>
      </div>
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

  </script>
  @include('partials.pwa-sw')
</body>
</html>
