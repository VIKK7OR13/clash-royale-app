@extends('layouts.app')

@section('titulo', 'VIK13 | Clash Royale')

@section('contenido')
    @php
        $herramientas = [
            ['/clash', 'Buscar jugador', 'Estadísticas, mazo actual y últimas partidas de cualquier jugador.', '/jugador/28PL90L0V'],
            ['/clan', 'Seguimiento de clan', 'Quién se conectó, hace cuánto y cuántos mazos usó cada miembro en la guerra.', '/clan/QPYV0UP0'],
            ['/comparar', 'Comparar mazos', 'Dos mazos lado a lado, con cartas en común, ciclo de 4 cartas y elixir promedio.', null],
        ];
    @endphp

    <main class="mx-auto my-16 max-w-[960px] px-5">
        <section class="text-center">
            <h1 class="text-4xl font-bold text-acento">VIK13 · Clash Royale</h1>
            <p class="mx-auto mt-4 max-w-[600px] text-lg text-gray-300">
                Herramientas para jugadores y líderes de clan, hechas con la API oficial de Supercell.
            </p>
        </section>

        <section class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ($herramientas as [$ruta, $titulo, $descripcion, $ejemplo])
                <div class="flex flex-col rounded-lg border-l-4 border-acento bg-tarjeta p-5">
                    <h2 class="text-xl font-bold">{{ $titulo }}</h2>
                    <p class="mt-2 flex-1 text-sm text-suave">{{ $descripcion }}</p>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ $ruta }}" class="rounded-lg bg-acento px-4 py-2 text-sm text-white hover:opacity-90">
                            Abrir
                        </a>
                        @if ($ejemplo)
                            <a href="{{ $ejemplo }}" class="rounded-lg border border-gray-600 px-4 py-2 text-sm hover:border-acento">
                                Ver ejemplo
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>

        <section class="mt-12 text-center text-sm text-suave">
            <p>Hecho con Laravel, Blade y Tailwind. Los datos se consultan en vivo a la API de Clash Royale.</p>
            <p class="mt-2">
                <a href="https://github.com/VIKK7OR13/clash-royale-app" class="text-acento hover:underline">
                    Ver el código en GitHub
                </a>
            </p>
        </section>
    </main>
@endsection