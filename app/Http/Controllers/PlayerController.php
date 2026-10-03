<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PlayerController extends Controller
{
    public function show()
    {
        $tag = urlencode('#28PL90L0V');

        $response = Http::withToken(config('services.clash_royale.key'))
            ->get("https://api.clashroyale.com/v1/players/{$tag}");

        $player = $response->json();

        return view('player', ['player' => $player]);
    }
}