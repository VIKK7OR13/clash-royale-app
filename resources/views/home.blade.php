@extends('layouts.app')

@section('titulo', 'VIK13 | Clash Royale')

@section('contenido')
    @php
        $redes = [
            'youtube' => 'https://www.youtube.com/@V_I_K_13',
            'instagram' => 'https://www.instagram.com/v_i_k_13',
            'tiktok' => 'https://www.tiktok.com/@vik134285',
        ];

        $herramientas = [
            ['/clash', 'Buscar jugador', 'Estadísticas, mazo actual y últimas partidas de cualquier jugador.', '/jugador/28PL90L0V'],
            ['/clan', 'Seguimiento de clan', 'Quién se conectó, hace cuánto y cuántos mazos usó cada miembro en la guerra.', '/clan/QPYV0UP0'],
            ['/comparar', 'Comparar mazos', 'Dos mazos lado a lado, con cartas en común, ciclo de 4 cartas y elixir promedio.', null],
        ];
    @endphp

    <main class="mx-auto max-w-[960px] px-5 py-16">
        <section class="text-center">
            <h1 class="text-4xl font-bold sm:text-5xl">
                <span class="text-acento">VIK13</span> · Clash Royale
            </h1>
            <p class="mx-auto mt-4 max-w-[560px] text-lg text-gray-300">
                Estadísticas, mazos y seguimiento de clan con datos en vivo de la API oficial.
            </p>

            <form method="GET" action="/clash" class="mx-auto mt-8 flex max-w-[520px] gap-2">
                <input
                    type="text"
                    name="tag"
                    placeholder="Tag del jugador, ej: #28PL90L0V"
                    required
                    class="flex-1 rounded-lg border border-gray-600 bg-tarjeta p-3 text-base text-gray-100"
                >
                <button
                    type="submit"
                    class="cursor-pointer rounded-lg bg-acento px-5 py-3 text-base font-semibold text-white hover:opacity-90"
                >
                    Buscar
                </button>
            </form>
        </section>

        <section class="mt-14 grid gap-6 md:grid-cols-3">
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

        <section class="mt-14 rounded-2xl border border-borde bg-tarjeta p-6 text-center sm:p-8">
            <h2 class="text-2xl font-bold">Seguime en mis redes</h2>
            <p class="mx-auto mt-2 max-w-[480px] text-suave">
                Partidas, shorts y novedades de Clash Royale.
            </p>

            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a
                    href="{{ $redes['youtube'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-3 font-semibold text-white hover:opacity-90"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <rect x="2" y="5" width="20" height="14" rx="4"/>
                        <path d="M10 9l5 3-5 3z" fill="currentColor"/>
                    </svg>
                    YouTube
                </a>

                <a
                    href="{{ $redes['instagram'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-lg bg-linear-to-r from-fuchsia-600 to-orange-500 px-5 py-3 font-semibold text-white hover:opacity-90"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <rect x="3" y="3" width="18" height="18" rx="5"/>
                        <circle cx="12" cy="12" r="4"/>
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
                    </svg>
                    Instagram
                </a>

                <a
                    href="{{ $redes['tiktok'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-600 bg-black px-5 py-3 font-semibold text-white hover:border-white"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="M9 18V5l12-2v13"/>
                        <circle cx="6" cy="18" r="3"/>
                        <circle cx="18" cy="16" r="3"/>
                    </svg>
                    TikTok
                </a>
            </div>
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