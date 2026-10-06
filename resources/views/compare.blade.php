<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIK13 | Comparar mazos</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #1e1e24; color: #f2f2f2; }
        .contenedor { max-width: 960px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #e63946; }
        h2 { margin: 0 0 8px; }
        a { color: #e63946; }
        form { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 24px; }
        input { flex: 1; min-width: 180px; padding: 12px; border-radius: 8px; border: 1px solid #444; background: #2a2a32; color: #f2f2f2; font-size: 1rem; }
        button { padding: 12px 20px; border: none; border-radius: 8px; background: #e63946; color: #fff; font-size: 1rem; cursor: pointer; }
        .error { background: #4a1c20; padding: 12px; border-radius: 8px; margin-top: 16px; }
        .resumen { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 32px; }
        .tarjeta { background: #2a2a32; border-left: 4px solid #e63946; border-radius: 8px; padding: 16px; }
        .tarjeta span { display: block; font-size: 0.85rem; color: #aaa; }
        .tarjeta strong { font-size: 1.4rem; }
        .duelo { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 32px; }
        .lado { background: #2a2a32; border-radius: 8px; padding: 16px; }
        .lado .datos { color: #aaa; font-size: 0.9rem; margin-bottom: 12px; }
        .mazo { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        .carta { background: #1e1e24; border: 2px solid transparent; border-radius: 8px; padding: 6px; text-align: center; }
        .carta.comun { border-color: #e9c46a; }
        .carta img { width: 100%; height: auto; }
        .carta p { margin: 2px 0 0; font-size: 0.75rem; }
        .elixir { color: #c77dff; font-weight: bold; }
        .leyenda { color: #aaa; font-size: 0.85rem; margin-top: 12px; }
        h3 { margin: 40px 0 8px; }
        table { width: 100%; border-collapse: collapse; background: #2a2a32; border-radius: 8px; }
        th, td { padding: 12px 16px; text-align: center; border-bottom: 1px solid #3a3a44; }
        th:first-child, td:first-child { text-align: left; color: #aaa; }
        th { font-size: 0.9rem; }
        small.aviso { display: block; margin-top: 40px; color: #888; }
        @media (max-width: 700px) { .duelo { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="contenedor">
        <p><a href="/clash">&larr; Buscar un jugador</a></p>
        <h1>VIK13 · Comparar mazos</h1>
        <p>Ingresá los tags de dos jugadores para ver sus mazos lado a lado.</p>

        <form method="GET" action="/comparar">
            <input type="text" name="tag1" placeholder="Jugador 1 (ej: #28PL90L0V)" value="{{ $tag1 ?? '' }}" required>
            <input type="text" name="tag2" placeholder="Jugador 2" value="{{ $tag2 ?? '' }}" required>
            <button type="submit">Comparar</button>
        </form>

        @isset($errores)
            @foreach ($errores as $e)
                <div class="error">{{ $e }}</div>
            @endforeach
        @endisset

        @isset($a)
            @php
                $fmt = fn ($v, $dec = 1) => $v === null ? '-' : number_format($v, $dec, ',', '.');
                $filas = [
                    ['Elixir promedio', $fmt($mA['promedio']), $fmt($mB['promedio'])],
                    ['Ciclo de 4 cartas (elixir)', $fmt($mA['ciclo'], 0), $fmt($mB['ciclo'], 0)],
                    ['Nivel promedio de las cartas', $fmt($mA['nivel']), $fmt($mB['nivel'])],
                    ['Cartas evolucionadas', (string) $mA['evoluciones'], (string) $mB['evoluciones']],
                    ['Trofeos actuales', $fmt($a['trophies'], 0), $fmt($b['trophies'], 0)],
                    ['% de victorias de la cuenta', $mA['winrate'] === null ? '-' : $fmt($mA['winrate']) . '%', $mB['winrate'] === null ? '-' : $fmt($mB['winrate']) . '%'],
                ];
            @endphp

            <div class="resumen">
                <div class="tarjeta">
                    <span>Cartas en común</span>
                    <strong>{{ $comunes->count() }} de 8</strong>
                </div>
            </div>

            <div class="duelo">
                @foreach ([['p' => $a, 'mazo' => $mazoA, 'prom' => $promedioA], ['p' => $b, 'mazo' => $mazoB, 'prom' => $promedioB]] as $lado)
                    <div class="lado">
                        <h2>{{ $lado['p']['name'] }}</h2>
                        <div class="datos">
                            {{ $lado['p']['tag'] }}
                            · {{ number_format($lado['p']['trophies'], 0, ',', '.') }} 🏆
                            · Nivel {{ $lado['p']['expLevel'] }}
                            @if ($lado['prom'] !== null) · <span class="elixir">{{ $fmt($lado['prom']) }} ⚡ promedio</span> @endif
                        </div>
                        <div class="mazo">
                            @foreach ($lado['mazo'] as $carta)
                                <div class="carta {{ $comunes->contains($carta['name']) ? 'comun' : '' }}">
                                    <img src="{{ $carta['iconUrls']['medium'] ?? '' }}" alt="{{ $carta['name'] }}">
                                    <p>{{ $carta['name'] }}</p>
                                    <p class="elixir">{{ $carta['elixirCost'] ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="leyenda">Las cartas con borde amarillo están en los dos mazos.</p>

            <h3>Comparación de métricas</h3>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>{{ $a['name'] }}</th>
                        <th>{{ $b['name'] }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filas as $fila)
                        <tr>
                            <td>{{ $fila[0] }}</td>
                            <td>{{ $fila[1] }}</td>
                            <td>{{ $fila[2] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="leyenda">
                El ciclo es la suma del costo de las 4 cartas más baratas del mazo.
                El nivel está expresado en la escala del juego (hasta 16).
                El porcentaje de victorias corresponde a toda la cuenta, no solo a este mazo.
            </p>
        @endisset

        <small class="aviso">Contenido no oficial. Este sitio no está afiliado, respaldado ni patrocinado por Supercell.</small>
    </div>
</body>
</html>