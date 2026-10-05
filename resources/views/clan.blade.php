<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $clan['name'] }} | VIK13 Seguimiento de clan</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #1e1e24; color: #f2f2f2; }
        .contenedor { max-width: 960px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #e63946; }
        h2 { margin-top: 40px; }
        a { color: #e63946; }
        .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 24px; }
        .tarjeta { background: #2a2a32; border-left: 4px solid #e63946; border-radius: 8px; padding: 16px; }
        .tarjeta span { display: block; font-size: 0.85rem; color: #aaa; }
        .tarjeta strong { font-size: 1.5rem; }
        .tabla-wrap { overflow-x: auto; margin-top: 16px; }
        table { width: 100%; border-collapse: collapse; background: #2a2a32; border-radius: 8px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #3a3a44; white-space: nowrap; }
        th { color: #aaa; font-size: 0.85rem; font-weight: normal; }
        .punto { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 8px; background: #888; }
        .verde { background: #2a9d8f; }
        .amarillo { background: #e9c46a; }
        .rojo { background: #e63946; }
        .nota { background: #2a2a32; padding: 12px 16px; border-radius: 8px; margin-top: 16px; color: #ccc; }
        small.aviso { display: block; margin-top: 40px; color: #888; }
    </style>
</head>
<body>
    @php
        $roles = ['leader' => 'Líder', 'coLeader' => 'Colíder', 'elder' => 'Veterano', 'member' => 'Miembro'];
        $inactivos = $miembros->filter(fn ($m) => ($m['dias'] ?? 0) >= 7)->count();
        $sinMazos = $miembros->filter(fn ($m) => $m['mazos_semana'] === 0)->count();
    @endphp

    <div class="contenedor">
        <p><a href="/clan">&larr; Buscar otro clan</a></p>
        <h1>{{ $clan['name'] }}</h1>
        <p>{{ $clan['tag'] }} · {{ $clan['description'] ?? '' }}</p>

        <div class="tarjetas">
            <div class="tarjeta"><span>Miembros</span><strong>{{ count($miembros) }}</strong></div>
            <div class="tarjeta"><span>Puntaje del clan</span><strong>{{ number_format($clan['clanScore'] ?? 0, 0, ',', '.') }}</strong></div>
            <div class="tarjeta"><span>Inactivos (7+ días)</span><strong>{{ $inactivos }}</strong></div>
            @if ($hayDatosGuerra)
                <div class="tarjeta"><span>Sin mazos en la guerra</span><strong>{{ $sinMazos }}</strong></div>
            @endif
        </div>

        @unless ($hayDatosGuerra)
            <div class="nota">No hay datos de la guerra de clanes en este momento, así que se muestra solo la última conexión.</div>
        @endunless

        <h2>Actividad de los miembros</h2>
        <p style="color:#aaa; margin-top: 0;">Ordenados del más inactivo al más activo.</p>

        <div class="tabla-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Jugador</th>
                        <th>Rol</th>
                        <th>Última conexión</th>
                        <th>Trofeos</th>
                        <th>Donaciones</th>
                        @if ($hayDatosGuerra)
                            <th>Mazos (semana)</th>
                            <th>Mazos (hoy)</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($miembros as $m)
                        @php
                            $d = $m['dias'];
                            $estado = $d === null ? '' : ($d >= 7 ? 'rojo' : ($d >= 3 ? 'amarillo' : 'verde'));
                        @endphp
                        <tr>
                            <td><span class="punto {{ $estado }}"></span>{{ $m['name'] }}</td>
                            <td>{{ $roles[$m['role']] ?? $m['role'] }}</td>
                            <td>{{ $m['ultima'] ? $m['ultima']->locale('es')->diffForHumans() : '-' }}</td>
                            <td>{{ number_format($m['trophies'], 0, ',', '.') }}</td>
                            <td>{{ $m['donations'] }}</td>
                            @if ($hayDatosGuerra)
                                <td>{{ $m['mazos_semana'] }}</td>
                                <td>{{ $m['mazos_hoy'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <small class="aviso">Contenido no oficial. Este sitio no está afiliado, respaldado ni patrocinado por Supercell.</small>
    </div>
</body>
</html>