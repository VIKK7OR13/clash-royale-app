<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIK13 | Buscar jugador</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #1e1e24; color: #f2f2f2; }
        .contenedor { max-width: 600px; margin: 80px auto; padding: 0 20px; text-align: center; }
        h1 { color: #e63946; }
        form { display: flex; gap: 8px; margin-top: 24px; }
        input { flex: 1; padding: 12px; border-radius: 8px; border: 1px solid #444; background: #2a2a32; color: #f2f2f2; font-size: 1rem; }
        button { padding: 12px 20px; border: none; border-radius: 8px; background: #e63946; color: #fff; font-size: 1rem; cursor: pointer; }
        .error { background: #4a1c20; padding: 12px; border-radius: 8px; margin-top: 16px; }
        small { display: block; margin-top: 40px; color: #888; }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>VIK13 · Clash Royale</h1>
        <p>Ingresá el tag de un jugador para ver sus estadísticas.</p>

        <form method="GET" action="/clash">
            <input type="text" name="tag" placeholder="Ej: #28PL90L0V" required>
            <button type="submit">Buscar</button>
        </form>

        @isset($error)
            <div class="error">{{ $error }}</div>
        @endisset

        <small>Contenido no oficial. Este sitio no está afiliado, respaldado ni patrocinado por Supercell.</small>
    </div>
</body>
</html>