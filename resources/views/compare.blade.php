@extends('layouts.app')

@section('titulo', 'VIK13 | Comparar mazos')

@section('contenido')
    <main class="mx-auto my-10 max-w-[960px] px-5">
        <a href="/clash" class="text-acento hover:underline">&larr; Buscar un jugador</a>

        <h1 class="mt-4 text-3xl font-bold text-acento">VIK13 · Comparar mazos</h1>
        <p class="mt-2">Ingresá los tags de dos jugadores para ver sus mazos lado a lado.</p>

        <form method="GET" action="/comparar" class="mt-6 flex flex-wrap gap-2">
            <input
                type="text"
                name="tag1"
                placeholder="Jugador 1 (ej: #28PL90L0V)"
                value="{{ $tag1 ?? '' }}"
                required
                class="min-w-[180px] flex-1 rounded-lg border border-gray-600 bg-tarjeta p-3 text-base text-gray-100"
            >
            <input
                type="text"
                name="tag2"
                placeholder="Jugador 2"
                value="{{ $tag2 ?? '' }}"
                required
                class="min-w-[180px] flex-1 rounded-lg border border-gray-600 bg-tarjeta p-3 text-base text-gray-100"
            >
            <button
                type="submit"
                class="cursor-pointer rounded-lg bg-acento px-5 py-3 text-base text-white hover:opacity-90"
            >
                Comparar
            </button>
        </form>

        @isset($errores)
            @foreach ($errores as $e)
                <div class="mt-4 rounded-lg bg-acento/20 p-3">{{ $e }}</div>
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
                $lados = [
                    ['p' => $a, 'mazo' => $mazoA, 'prom' => $promedioA],
                    ['p' => $b, 'mazo' => $mazoB, 'prom' => $promedioB],
                ];
            @endphp

            <div class="mt-8 grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-4">
                <div class="rounded-lg border-l-4 border-acento bg-tarjeta p-4">
                    <span class="block text-sm text-suave">Cartas en común</span>
                    <strong class="text-2xl">{{ $comunes->count() }} de 8</strong>
                </div>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-2">
                @foreach ($lados as $lado)
                    <div class="rounded-lg bg-tarjeta p-4">
                        <h2 class="text-xl font-bold">{{ $lado['p']['name'] }}</h2>
                        <div class="mb-3 mt-1 text-sm text-suave">
                            {{ $lado['p']['tag'] }}
                            · {{ number_format($lado['p']['trophies'], 0, ',', '.') }} 🏆
                            · Nivel {{ $lado['p']['expLevel'] }}
                            @if ($lado['prom'] !== null)
                                · <span class="font-bold text-elixir">{{ $fmt($lado['prom']) }} ⚡ promedio</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach ($lado['mazo'] as $carta)
                                <div class="rounded-lg border-2 bg-fondo p-1.5 text-center {{ $comunes->contains($carta['name']) ? 'border-aviso' : 'border-transparent' }}">
                                    <img src="{{ $carta['iconUrls']['medium'] ?? '' }}" alt="{{ $carta['name'] }}" class="h-auto w-full">
                                    <p class="mt-0.5 text-xs">{{ $carta['name'] }}</p>
                                    <p class="mt-0.5 text-xs font-bold text-elixir">{{ $carta['elixirCost'] ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="mt-3 text-sm text-suave">Las cartas con borde amarillo están en los dos mazos.</p>

            <h3 class="mb-2 mt-10 text-xl font-bold">Comparación de métricas</h3>
            <table class="w-full border-collapse rounded-lg bg-tarjeta">
                <thead>
                    <tr class="border-b border-borde text-sm">
                        <th class="px-4 py-3"></th>
                        <th class="px-4 py-3">{{ $a['name'] }}</th>
                        <th class="px-4 py-3">{{ $b['name'] }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filas as $fila)
                        <tr class="border-b border-borde last:border-b-0">
                            <td class="px-4 py-3 text-left text-suave">{{ $fila[0] }}</td>
                            <td class="px-4 py-3 text-center">{{ $fila[1] }}</td>
                            <td class="px-4 py-3 text-center">{{ $fila[2] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="mt-3 text-sm text-suave">
                El ciclo es la suma del costo de las 4 cartas más baratas del mazo.
                El nivel está expresado en la escala del juego (hasta 16).
                El porcentaje de victorias corresponde a toda la cuenta, no solo a este mazo.
            </p>
        @endisset
    </main>
@endsection