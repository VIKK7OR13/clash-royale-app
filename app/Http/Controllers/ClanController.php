<?php

namespace App\Http\Controllers;

use App\Services\ClashRoyale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClanController extends Controller
{
    public function search(Request $request)
    {
        if ($request->filled('tag')) {
            return redirect('/clan/' . ClashRoyale::limpiarTag($request->input('tag')));
        }

        return view('clan-search');
    }

    public function show(string $tag)
    {
        $tag = ClashRoyale::limpiarTag($tag);

        if (! ClashRoyale::tagValido($tag)) {
            return view('clan-search', ['error' => 'El tag no es válido. Revisá que esté bien escrito.']);
        }

        $clan = Cache::get("clan_{$tag}");

        if (! $clan) {
            $response = ClashRoyale::get('/clans/' . urlencode('#' . $tag));

            if (! $response->successful()) {
                $reason = $response->json('reason') ?? 'No se pudo conectar con la API';

                $mensaje = $reason === 'notFound'
                    ? 'No se encontró ningún clan con ese tag.'
                    : "No se pudieron cargar los datos: {$reason}";

                return view('clan-search', ['error' => $mensaje]);
            }

            $clan = $response->json();
            Cache::put("clan_{$tag}", $clan, now()->addMinutes(5));
        }

        $guerra = Cache::get("guerra_{$tag}");

        if ($guerra === null) {
            $response = ClashRoyale::get('/clans/' . urlencode('#' . $tag) . '/currentriverrace');
            $guerra = $response->successful() ? $response->json() : [];

            if ($response->successful()) {
                Cache::put("guerra_{$tag}", $guerra, now()->addMinutes(5));
            }
        }

        $hayDatosGuerra = ! empty($guerra);
        $participantes = collect($guerra['clan']['participants'] ?? [])->keyBy('tag');
        $ahora = now()->timestamp;

        $miembros = collect($clan['memberList'] ?? [])->map(function ($m) use ($participantes, $ahora) {
            $ultima = isset($m['lastSeen'])
                ? Carbon::createFromFormat('Ymd\THis.v\Z', $m['lastSeen'], 'UTC')
                : null;

            $p = $participantes->get($m['tag']);

            return [
                'name' => $m['name'],
                'role' => $m['role'] ?? 'member',
                'trophies' => $m['trophies'] ?? 0,
                'donations' => $m['donations'] ?? 0,
                'ultima' => $ultima,
                'dias' => $ultima ? intdiv($ahora - $ultima->timestamp, 86400) : null,
                'mazos_semana' => $p['decksUsed'] ?? 0,
                'mazos_hoy' => $p['decksUsedToday'] ?? 0,
            ];
        })->sortByDesc(fn ($m) => $m['dias'] ?? 0)->values();

        return view('clan', [
            'clan' => $clan,
            'miembros' => $miembros,
            'hayDatosGuerra' => $hayDatosGuerra,
        ]);
    }
}