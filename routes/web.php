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



// 🔹 Sistem Pakar Musik
Route::get('/', [MusicExpertController::class, 'index']);
Route::post('/music-expert/recommend', [MusicExpertController::class, 'recommend']);
