<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::get('/link/{uuid}', [LinkController::class, 'show'])->name('link.show');
Route::post('/link/{uuid}/generate', [LinkController::class, 'generateNewLink'])->name('link.generate');
Route::post('/link/{uuid}/deactivate', [LinkController::class, 'deactivateLink'])->name('link.deactivate');

Route::post('/link/{uuid}/play', [GameController::class, 'playLucky'])->name('game.play');
Route::get('/link/{uuid}/history', [GameController::class, 'history'])->name('game.history');
