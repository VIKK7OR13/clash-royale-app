@extends('layouts.app')

@section('titulo', 'VIK13 | Buscar clan')

@section('contenido')
    <main class="mx-auto mt-20 max-w-[600px] px-5 text-center">
        <h1 class="text-3xl font-bold text-acento">VIK13 · Seguimiento de clan</h1>
        <p class="mt-4">Ingresá el tag de un clan para ver quién juega y quién no.</p>

        <form method="GET" action="/clan" class="mt-6 flex gap-2">
            <input
                type="text"
                name="tag"
                placeholder="Ej: #QPYV0UP0"
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

        <p class="mt-6">
            <a href="/clash" class="text-acento hover:underline">Buscar un jugador</a>
        </p>
    </main>
@endsection