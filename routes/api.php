<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JogoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('v1', function() { return response()->json(['Versão: 1.0.0']); });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('games/todos', [JogoController::class, 'index']);
    Route::post('games/salvar', [JogoController::class, 'store']);
    Route::get('games/{id}', [JogoController::class, 'show']);
    Route::put('games/{id}', [JogoController::class, 'update']);
    Route::delete('games/{id}', [JogoController::class, 'destroy']);
});
