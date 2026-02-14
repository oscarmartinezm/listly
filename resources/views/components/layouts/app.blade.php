<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('partials.pwa-head')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-100">
  {{-- Navigation --}}
  <nav class="bg-white shadow-sm sticky top-0 z-50">
  <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between">
    <a href="{{ route('home') }}" class="font-bold text-lg text-gray-800">
    Listly
    </a>
    <div class="flex items-center gap-3">
    <a href="{{ route('home') }}"
       class="text-sm px-3 py-1.5 rounded-lg {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
      Inicio
    </a>
    <a href="{{ route('admin') }}"
       class="text-sm px-3 py-1.5 rounded-lg {{ request()->routeIs('admin') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
      Admin
    </a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="text-sm px-3 py-1.5 rounded-lg text-gray-600 hover:bg-gray-100">
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
  @include('partials.pwa-sw')
</body>
</html>
