<?php

namespace App\Http\Controllers;

use App\Services\ClashRoyale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CompareController extends Controller
{
    private function jugador(string $tag): array
    {
        if (! ClashRoyale::tagValido($tag)) {
            return ['error' => "El tag #{$tag} no es válido."];
        }

        $player = Cache::get("player_{$tag}");

        if (! $player) {
            $response = ClashRoyale::get('/players/' . urlencode('#' . $tag));

            if (! $response->successful()) {
                $reason = $response->json('reason');

                return ['error' => $reason === 'notFound'
                    ? "No se encontró ningún jugador con el tag #{$tag}."
                    : "No se pudieron cargar los datos de #{$tag}."];
            }

            $player = $response->json();
            Cache::put("player_{$tag}", $player, now()->addMinutes(5));
        }

        return ['player' => $player];
    }

    private function metricas(array $player): array
    {
        $mazo = collect($player['currentDeck'] ?? []);

        $costos = $mazo->pluck('elixirCost')->filter(fn ($c) => $c !== null)->sort()->values();

        $niveles = $mazo->map(fn ($c) => 16 - ($c['maxLevel'] ?? 16) + ($c['level'] ?? 0));

        $partidas = $player['battleCount'] ?? 0;

        return [
            'promedio' => $costos->count() ? round($costos->avg(), 1) : null,
            'ciclo' => $costos->count() >= 4 ? $costos->take(4)->sum() : null,
            'nivel' => $niveles->count() ? round($niveles->avg(), 1) : null,
            'evoluciones' => $mazo->filter(fn ($c) => ($c['evolutionLevel'] ?? 0) > 0)->count(),
            'winrate' => $partidas > 0 ? round(($player['wins'] ?? 0) / $partidas * 100, 1) : null,
        ];
    }

    public function index(Request $request)
    {
        if (! $request->filled('tag1') || ! $request->filled('tag2')) {
            return view('compare');
        }

        $tag1 = ClashRoyale::limpiarTag($request->input('tag1'));
        $tag2 = ClashRoyale::limpiarTag($request->input('tag2'));

        $a = $this->jugador($tag1);
        $b = $this->jugador($tag2);

        $errores = array_values(array_filter([$a['error'] ?? null, $b['error'] ?? null]));

        if ($errores) {
            return view('compare', [
                'errores' => $errores,
                'tag1' => $tag1,
                'tag2' => $tag2,
            ]);
        }

        $mazoA = collect($a['player']['currentDeck'] ?? []);
        $mazoB = collect($b['player']['currentDeck'] ?? []);

        $mA = $this->metricas($a['player']);
        $mB = $this->metricas($b['player']);

        return view('compare', [
            'a' => $a['player'],
            'b' => $b['player'],
            'mazoA' => $mazoA,
            'mazoB' => $mazoB,
            'comunes' => $mazoA->pluck('name')->intersect($mazoB->pluck('name'))->values(),
            'promedioA' => $mA['promedio'],
            'promedioB' => $mB['promedio'],
            'mA' => $mA,
            'mB' => $mB,
            'tag1' => $tag1,
            'tag2' => $tag2,
        ]);
    }
}