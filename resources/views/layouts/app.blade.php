<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'VIK13 | Clash Royale')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="m-0 flex min-h-screen flex-col bg-fondo font-sans text-gray-100">
    <div class="flex-1">
        @yield('contenido')
    </div>

    <footer class="px-5 py-6 text-center text-sm text-gray-500">
        Contenido no oficial. Este sitio no está afiliado, respaldado ni patrocinado por Supercell.
    </footer>
</body>
</html>