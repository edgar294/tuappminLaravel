<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\NotificacionUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ZonaComunController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\Administracion;

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

    /** Residente */
    Route::resource('residente', ResidenteController::class)->except(['create', 'edit']);
    Route::get('residentes/get-all-paginate', [ResidenteController::class, 'get_all_paginate']);

    /** Noticia */
    Route::resource('noticia', NoticiaController::class)->except(['create', 'edit']);
    Route::get('noticias/get-all-paginate', [NoticiaController::class, 'get_all_paginate']);

    /** Notificacion */
    Route::resource('notificacion_user', NotificacionUserController::class)->except(['create', 'edit']);
    Route::get('notificaciones_users/get-all-paginate', [NotificacionUserController::class, 'get_all_paginate']);

    /** Chat */
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('get-conversaciones', [ChatController::class, 'get_conversaciones']);
    Route::get('messages', [ChatController::class, 'getMessages']);
    Route::post('send-message', [ChatController::class, 'sendMessage']);
    Route::post('nuevo-chat', [ChatController::class, 'nuevoChat']);

    /** Configuracion */
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/save', [ConfiguracionController::class, 'save'])->name('configuracion.save');

    /** Apartamento */
    Route::resource('apartamento', Administracion\ApartamentoController::class)->except(['create', 'edit', 'update']);
    Route::get('apartamentos/get-all-paginate', [Administracion\ApartamentoController::class, 'get_all_paginate']);
    Route::get('apartamentos/get-all', [Administracion\ApartamentoController::class, 'get_all']);

    /** Casa */
    Route::resource('casa', Administracion\CasaController::class)->except(['create', 'edit', 'update']);
    Route::get('casas/get-all-paginate', [Administracion\CasaController::class, 'get_all_paginate']);
    Route::get('casas/get-all', [Administracion\CasaController::class, 'get_all']);

    /** Zonas comunes */
    Route::resource('zona_comun', Administracion\ZonaComunController::class)->except(['create', 'edit']);
    Route::get('zonas_comunes/get-all-paginate', [Administracion\ZonaComunController::class, 'get_all_paginate']);
});
