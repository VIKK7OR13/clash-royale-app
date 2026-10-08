<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ClashRoyale
{
    public static function limpiarTag(string $tag): string
    {
        $tag = strtoupper(trim($tag));
        $tag = ltrim($tag, '#');

        return str_replace('O', '0', $tag);
    }

    public static function tagValido(string $tag): bool
    {
        return (bool) preg_match('/^[0289PYLQGRJCUV]{3,15}$/', $tag);
    }

    public static function get(string $ruta): Response
    {
        return Http::withToken(config('services.clash_royale.key'))
            ->get(rtrim(config('services.clash_royale.base_url'), '/') . $ruta);
    }
}