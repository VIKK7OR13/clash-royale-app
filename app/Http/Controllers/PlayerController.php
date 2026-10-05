<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PlayerController extends Controller
{
    private function limpiarTag(string $tag): string
    {
        $tag = strtoupper(trim($tag));
        $tag = ltrim($tag, '#');

        return str_replace('O', '0', $tag);
    }

    private function batallas(string $tag): array
    {
        $batallas = Cache::get("batallas_{$tag}");

        if ($batallas === null) {
            $response = Http::withToken(config('services.clash_royale.key'))
                ->get('https://api.clashroyale.com/v1/players/' . urlencode('#' . $tag) . '/battlelog');

            if (! $response->successful()) {
                return [];
            }

            $batallas = array_slice($response->json(), 0, 10);
            Cache::put("batallas_{$tag}", $batallas, now()->addMinutes(2));
        }

        return $batallas;
    }

    public function search(Request $request)
    {
        if ($request->filled('tag')) {
            return redirect('/jugador/' . $this->limpiarTag($request->input('tag')));
        }

        return view('search');
    }

    public function show(string $tag)
    {
        $tag = $this->limpiarTag($tag);

        if (! preg_match('/^[0289PYLQGRJCUV]{3,15}$/', $tag)) {
            return view('search', ['error' => 'El tag no es válido. Revisá que esté bien escrito.']);
        }

        $player = Cache::get("player_{$tag}");

        if (! $player) {
            $response = Http::withToken(config('services.clash_royale.key'))
                ->get('https://api.clashroyale.com/v1/players/' . urlencode('#' . $tag));

            if (! $response->successful()) {
                $reason = $response->json('reason') ?? 'No se pudo conectar con la API';

                $mensaje = $reason === 'notFound'
                    ? 'No se encontró ningún jugador con ese tag.'
                    : "No se pudieron cargar los datos: {$reason}";

                return view('search', ['error' => $mensaje]);
            }

            $player = $response->json();
            Cache::put("player_{$tag}", $player, now()->addMinutes(5));
        }

        return view('player', [
            'player' => $player,
            'batallas' => $this->batallas($tag),
        ]);
    }
}