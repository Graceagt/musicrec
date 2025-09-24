<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicExpertController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Halaman tes saja (sbp.blade.php)
Route::get('/sbp', function () {
    return view('sbp');
});

// 🔹 Sistem Pakar Musik
Route::get('/music-expert', [MusicExpertController::class, 'index']);
Route::post('/music-expert/recommend', [MusicExpertController::class, 'recommend']);
