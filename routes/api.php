<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    /** Auth registro, login y verificacion de email */
    Route::post('login', [Api\LoginController::class, 'login']);
    Route::post('logout', [Api\LoginController::class, 'logout']);
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('auth/verificar-token', [Api\LoginController::class, 'verificar_token']);

    /** Perfil */
    Route::group([
        'middleware' => 'api',
        'prefix' => 'perfil/'
    ], function ($router) {
        Route::post('update', [Api\PerfilController::class, 'update']);
        Route::post('update_avatar', [Api\PerfilController::class, 'update_avatar']);
        Route::get('get_perfil', [Api\PerfilController::class, 'get_perfil']);
    });
});
