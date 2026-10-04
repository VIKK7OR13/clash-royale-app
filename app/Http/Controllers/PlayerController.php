<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PlayerController extends Controller
{
    public function show()
    {
        $player = Cache::get('clash_player');

        if (! $player) {
            $tag = urlencode('#28PL90L0V');

            $response = Http::withToken(config('services.clash_royale.key'))
                ->get("https://api.clashroyale.com/v1/players/{$tag}");

            if ($response->successful()) {
                $player = $response->json();
                Cache::put('clash_player', $player, now()->addMinutes(5));
            } else {
                $player = $response->json() ?? ['reason' => 'No se pudo conectar con la API'];
            }
        }

        return view('player', ['player' => $player]);
    }
}