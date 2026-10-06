@extends('layouts.app')

@section('titulo', $player['name'] . ' | VIK13 Clash Royale')

@section('contenido')
    @php
        $mazo = $player['currentDeck'] ?? [];
        $promedio = count($mazo) ? number_format(collect($mazo)->avg('elixirCost'), 1, ',', '.') : null;

        $estadisticas = [
            ['Trofeos', number_format($player['trophies'], 0, ',', '.')],
            ['Mejores trofeos', number_format($player['bestTrophies'], 0, ',', '.')],
            ['Nivel', $player['expLevel']],
            ['Victorias', number_format($player['wins'], 0, ',', '.')],
            ['Partidas jugadas', number_format($player['battleCount'], 0, ',', '.')],
            ['Victorias con 3 coronas', number_format($player['threeCrownWins'], 0, ',', '.')],
        ];

        $bordes = [
            'victoria' => 'border-ok',
            'derrota' => 'border-acento',
            'empate' => 'border-gray-500',
        ];
    @endphp

    <main class="mx-auto my-10 max-w-[800px] px-5">
        <a href="/clash" class="text-acento hover:underline">&larr; Buscar otro jugador</a>

        <h1 class="mt-4 text-3xl font-bold text-acento">{{ $player['name'] }}</h1>
        <p class="mt-2">
            {{ $player['tag'] }}
            · Clan: {{ $player['clan']['name'] ?? 'Sin clan' }}
            · Arena: {{ $player['arena']['name'] ?? '-' }}
        </p>

        <div class="mt-6 grid grid-cols-[repeat(auto-fit,minmax(180px,1fr))] gap-4">
            @foreach ($estadisticas as [$etiqueta, $valor])
                <div class="rounded-lg border-l-4 border-acento bg-tarjeta p-4">
                    <span class="block text-sm text-suave">{{ $etiqueta }}</span>
                    <strong class="text-2xl">{{ $valor }}</strong>
                </div>
            @endforeach
        </div>

        @if (count($mazo))
            <h2 class="mt-10 text-2xl font-bold">
                Mazo actual
                @if ($promedio)
                    <span class="font-bold text-elixir">· {{ $promedio }} de elixir promedio</span>
                @endif
            </h2>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ($mazo as $carta)
                    <div class="rounded-lg bg-tarjeta p-2 text-center">
                        <img src="{{ $carta['iconUrls']['medium'] ?? '' }}" alt="{{ $carta['name'] }}" class="h-auto w-full">
                        <p class="mt-1 text-xs">{{ $carta['name'] }}</p>
                        <p class="mt-1 text-xs font-bold text-elixir">{{ $carta['elixirCost'] ?? '-' }} ⚡</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if (count($batallas))
            <h2 class="mt-10 text-2xl font-bold">Últimas partidas</h2>
            <div class="mt-4 grid gap-2.5">
                @foreach ($batallas as $b)
                    @php
                        $mias = $b['team'][0]['crowns'] ?? 0;
                        $rival = $b['opponent'][0]['crowns'] ?? 0;
                        $resultado = $mias > $rival ? 'victoria' : ($mias < $rival ? 'derrota' : 'empate');
                        $hace = \Carbon\Carbon::createFromFormat('Ymd\THis.v\Z', $b['battleTime'], 'UTC')->locale('es')->diffForHumans();
                        $cambio = $b['team'][0]['trophyChange'] ?? null;
                    @endphp
                    <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2 rounded-lg border-l-4 bg-tarjeta px-4 py-3 {{ $bordes[$resultado] }}">
                        <div>
                            <strong>{{ ucfirst($resultado) }}</strong>
                            · {{ $mias }} - {{ $rival }} vs {{ $b['opponent'][0]['name'] ?? '?' }}
                        </div>
                        <div class="text-sm text-suave">
                            {{ $b['gameMode']['name'] ?? $b['type'] }}
                            @if ($cambio !== null) · {{ $cambio > 0 ? '+' : '' }}{{ $cambio }} 🏆 @endif
                            · {{ $hace }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
@endsection