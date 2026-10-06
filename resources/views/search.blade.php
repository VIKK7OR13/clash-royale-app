@extends('layouts.app')

@section('titulo', 'VIK13 | Buscar jugador')

@section('contenido')
    <main class="mx-auto mt-20 max-w-[600px] px-5 text-center">
        <h1 class="text-3xl font-bold text-acento">VIK13 · Clash Royale</h1>
        <p class="mt-4">Ingresá el tag de un jugador para ver sus estadísticas.</p>

        <form method="GET" action="/clash" class="mt-6 flex gap-2">
            <input
                type="text"
                name="tag"
                placeholder="Ej: #28PL90L0V"
                required
                class="flex-1 rounded-lg border border-gray-600 bg-tarjeta p-3 text-base text-gray-100"
            >
            <button
                type="submit"
                class="cursor-pointer rounded-lg bg-acento px-5 py-3 text-base text-white hover:opacity-90"
            >
                Buscar
            </button>
        </form>

        @isset($error)
            <div class="mt-4 rounded-lg bg-acento/20 p-3">{{ $error }}</div>
        @endisset
    </main>
@endsection