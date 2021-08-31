<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function() {
    /** Usuario */
    Route::resource('usuario', UserController::class)->except(['create', 'edit', 'destroy']);
    Route::get('usuarios/get-all-paginate', [UserController::class, 'get_all_paginate']);
    Route::post('usuarios/change-status/{id}', [UserController::class, 'change_status']);
    Route::get('usuarios/get-all', [UserController::class, 'get_all']);

    /** Noticia */
    Route::resource('noticia', NoticiaController::class)->except(['create', 'edit']);
    Route::get('noticias/get-all-paginate', [NoticiaController::class, 'get_all_paginate']);

    /** Notificacion */
    Route::resource('notificacion', NotificacionController::class)->except(['create', 'edit']);
    Route::get('notificaciones/get-all-paginate', [NotificacionController::class, 'get_all_paginate']);

    /** Chat */
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('get-conversaciones', [ChatController::class, 'get_conversaciones']);
    Route::get('messages', [ChatController::class, 'getMessages']);
    Route::post('send-message', [ChatController::class, 'sendMessage']);
    Route::post('nuevo-chat', [ChatController::class, 'nuevoChat']);
});
