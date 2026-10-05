<?php

use App\Http\Controllers\ClanController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clash', [PlayerController::class, 'search']);
Route::get('/jugador/{tag}', [PlayerController::class, 'show']);

Route::get('/clan', [ClanController::class, 'search']);
Route::get('/clan/{tag}', [ClanController::class, 'show']);