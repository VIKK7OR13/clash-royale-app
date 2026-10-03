<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIK13 | Clash Royale</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #1e1e24; color: #f2f2f2; }
        .contenedor { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #e63946; }
        .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 24px; }
        .tarjeta { background: #2a2a32; border-left: 4px solid #e63946; border-radius: 8px; padding: 16px; }
        .tarjeta span { display: block; font-size: 0.85rem; color: #aaa; }
        .tarjeta strong { font-size: 1.5rem; }
        .error { background: #4a1c20; padding: 16px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>VIK13 · Clash Royale</h1>

        @if (isset($player['reason']))
            <div class="error">No se pudieron cargar los datos: {{ $player['reason'] }}</div>
        @else
            <p>
                Jugador: <strong>{{ $player['name'] }}</strong>
                · Clan: {{ $player['clan']['name'] ?? 'Sin clan' }}
                · Arena: {{ $player['arena']['name'] ?? '-' }}
            </p>

            <div class="tarjetas">
                <div class="tarjeta"><span>Trofeos</span><strong>{{ number_format($player['trophies'], 0, ',', '.') }}</strong></div>
                <div class="tarjeta"><span>Mejores trofeos</span><strong>{{ number_format($player['bestTrophies'], 0, ',', '.') }}</strong></div>
                <div class="tarjeta"><span>Nivel</span><strong>{{ $player['expLevel'] }}</strong></div>
                <div class="tarjeta"><span>Victorias</span><strong>{{ number_format($player['wins'], 0, ',', '.') }}</strong></div>
                <div class="tarjeta"><span>Partidas jugadas</span><strong>{{ number_format($player['battleCount'], 0, ',', '.') }}</strong></div>
                <div class="tarjeta"><span>Victorias con 3 coronas</span><strong>{{ number_format($player['threeCrownWins'], 0, ',', '.') }}</strong></div>
            </div>
        @endif
    </div>
</body>
</html>