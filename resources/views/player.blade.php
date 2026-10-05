<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player['name'] }} | VIK13 Clash Royale</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #1e1e24; color: #f2f2f2; }
        .contenedor { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #e63946; }
        h2 { margin-top: 40px; }
        a { color: #e63946; }
        .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 24px; }
        .tarjeta { background: #2a2a32; border-left: 4px solid #e63946; border-radius: 8px; padding: 16px; }
        .tarjeta span { display: block; font-size: 0.85rem; color: #aaa; }
        .tarjeta strong { font-size: 1.5rem; }
        .mazo { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 16px; }
        .carta { background: #2a2a32; border-radius: 8px; padding: 8px; text-align: center; }
        .carta img { width: 100%; height: auto; }
        .carta p { margin: 4px 0 0; font-size: 0.8rem; }
        .elixir { color: #c77dff; font-weight: bold; }
        .batallas { display: grid; gap: 10px; margin-top: 16px; }
        .batalla { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 8px 16px; background: #2a2a32; border-left: 4px solid #888; border-radius: 8px; padding: 12px 16px; }
        .batalla.victoria { border-left-color: #2a9d8f; }
        .batalla.derrota { border-left-color: #e63946; }
        .batalla .detalle { color: #aaa; font-size: 0.85rem; }
        small.aviso { display: block; margin-top: 40px; color: #888; }
        @media (max-width: 500px) { .mazo { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>
<body>
    @php
        $mazo = $player['currentDeck'] ?? [];
        $promedio = count($mazo) ? number_format(collect($mazo)->avg('elixirCost'), 1, ',', '.') : null;
    @endphp

    <div class="contenedor">
        <p><a href="/clash">&larr; Buscar otro jugador</a></p>
        <h1>{{ $player['name'] }}</h1>
        <p>
            {{ $player['tag'] }}
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

        @if (count($mazo))
            <h2>Mazo actual @if ($promedio) <span class="elixir">· {{ $promedio }} de elixir promedio</span> @endif</h2>
            <div class="mazo">
                @foreach ($mazo as $carta)
                    <div class="carta">
                        <img src="{{ $carta['iconUrls']['medium'] ?? '' }}" alt="{{ $carta['name'] }}">
                        <p>{{ $carta['name'] }}</p>
                        <p class="elixir">{{ $carta['elixirCost'] ?? '-' }} ⚡</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if (count($batallas))
            <h2>Últimas partidas</h2>
            <div class="batallas">
                @foreach ($batallas as $b)
                    @php
                        $mias = $b['team'][0]['crowns'] ?? 0;
                        $rival = $b['opponent'][0]['crowns'] ?? 0;
                        $resultado = $mias > $rival ? 'victoria' : ($mias < $rival ? 'derrota' : 'empate');
                        $hace = \Carbon\Carbon::createFromFormat('Ymd\THis.v\Z', $b['battleTime'], 'UTC')->locale('es')->diffForHumans();
                        $cambio = $b['team'][0]['trophyChange'] ?? null;
                    @endphp
                    <div class="batalla {{ $resultado }}">
                        <div>
                            <strong>{{ ucfirst($resultado) }}</strong>
                            · {{ $mias }} - {{ $rival }} vs {{ $b['opponent'][0]['name'] ?? '?' }}
                        </div>
                        <div class="detalle">
                            {{ $b['gameMode']['name'] ?? $b['type'] }}
                            @if ($cambio !== null) · {{ $cambio > 0 ? '+' : '' }}{{ $cambio }} 🏆 @endif
                            · {{ $hace }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <small class="aviso">Contenido no oficial. Este sitio no está afiliado, respaldado ni patrocinado por Supercell.</small>
    </div>
</body>
</html>