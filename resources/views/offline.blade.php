<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sin conexion</title>
  <script>
  (function() {
    var s = localStorage.getItem('theme');
    if (s === 'dark' || (!s && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    }
  })();
  </script>
  <style>
    body {
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      background: #f5f5f5;
      color: #333;
      text-align: center;
    }
    .dark body {
      background: #111827;
      color: #e5e7eb;
    }
  </style>
</head>
<body>
  <div>
    <h1>Sin conexion</h1>
    <p>No tienes conexion a internet. Verifica tu conexion e intenta de nuevo.</p>
  </div>
</body>
</html>
