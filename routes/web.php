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
Route::get('/', [MusicExpertController::class, 'index'])->name('music-expert');
Route::post('/recommend', [MusicExpertController::class, 'recommend'])->name('music.recommend');
Route::get('/music', [MusicExpertController::class, 'index'])->name('music.index');
