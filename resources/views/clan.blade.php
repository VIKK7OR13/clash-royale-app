@extends('layouts.app')

@section('titulo', $clan['name'] . ' | VIK13 Seguimiento de clan')

@section('contenido')
    @php
        $roles = ['leader' => 'Líder', 'coLeader' => 'Colíder', 'elder' => 'Veterano', 'member' => 'Miembro'];
        $colores = ['rojo' => 'bg-acento', 'amarillo' => 'bg-aviso', 'verde' => 'bg-ok', '' => 'bg-gray-500'];

        $inactivos = $miembros->filter(fn ($m) => ($m['dias'] ?? 0) >= 7)->count();
        $sinMazos = $miembros->filter(fn ($m) => $m['mazos_semana'] === 0)->count();

        $resumen = [
            ['Miembros', count($miembros)],
            ['Puntaje del clan', number_format($clan['clanScore'] ?? 0, 0, ',', '.')],
            ['Inactivos (7+ días)', $inactivos],
        ];

        $columnas = ['Jugador', 'Rol', 'Última conexión', 'Trofeos', 'Donaciones'];

        if ($hayDatosGuerra) {
            $resumen[] = ['Sin mazos en la guerra', $sinMazos];
            $columnas[] = 'Mazos (semana)';
            $columnas[] = 'Mazos (hoy)';
        }
    @endphp

    <main class="mx-auto my-10 max-w-[960px] px-5">
        <a href="/clan" class="text-acento hover:underline">&larr; Buscar otro clan</a>

        <h1 class="mt-4 text-3xl font-bold text-acento">{{ $clan['name'] }}</h1>
        <p class="mt-2">{{ $clan['tag'] }} · {{ $clan['description'] ?? '' }}</p>

        <div class="mt-6 grid grid-cols-[repeat(auto-fit,minmax(180px,1fr))] gap-4">
            @foreach ($resumen as [$etiqueta, $valor])
                <div class="rounded-lg border-l-4 border-acento bg-tarjeta p-4">
                    <span class="block text-sm text-suave">{{ $etiqueta }}</span>
                    <strong class="text-2xl">{{ $valor }}</strong>
                </div>
            @endforeach
        </div>

        @unless ($hayDatosGuerra)
            <div class="mt-4 rounded-lg bg-tarjeta px-4 py-3 text-gray-300">
                No hay datos de la guerra de clanes en este momento, así que se muestra solo la última conexión.
            </div>
        @endunless

        <h2 class="mt-10 text-2xl font-bold">Actividad de los miembros</h2>
        <p class="mt-1 text-suave">Ordenados del más inactivo al más activo.</p>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full border-collapse rounded-lg bg-tarjeta">
                <thead>
                    <tr class="text-left text-sm text-suave">
                        @foreach ($columnas as $columna)
                            <th class="whitespace-nowrap px-3 py-2.5 font-normal">{{ $columna }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($miembros as $m)
                        @php
                            $d = $m['dias'];
                            $estado = $d === null ? '' : ($d >= 7 ? 'rojo' : ($d >= 3 ? 'amarillo' : 'verde'));
                        @endphp
                        <tr class="border-t border-borde">
                            <td class="whitespace-nowrap px-3 py-2.5">
                                <span class="mr-2 inline-block size-2.5 rounded-full {{ $colores[$estado] }}"></span>{{ $m['name'] }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-2.5">{{ $roles[$m['role']] ?? $m['role'] }}</td>
                            <td class="whitespace-nowrap px-3 py-2.5">{{ $m['ultima'] ? $m['ultima']->locale('es')->diffForHumans() : '-' }}</td>
                            <td class="whitespace-nowrap px-3 py-2.5">{{ number_format($m['trophies'], 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-3 py-2.5">{{ $m['donations'] }}</td>
                            @if ($hayDatosGuerra)
                                <td class="whitespace-nowrap px-3 py-2.5">{{ $m['mazos_semana'] }}</td>
                                <td class="whitespace-nowrap px-3 py-2.5">{{ $m['mazos_hoy'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection