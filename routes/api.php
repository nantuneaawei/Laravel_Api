<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Player\GetController;
use App\Http\Controllers\Player\PostController;
use App\Http\Controllers\Player\PatchController;
use App\Http\Controllers\Player\DeleteController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\JwtMiddleware;

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/players', [GetController::class, 'index']);

    Route::get('/players/{id}', [GetController::class, 'show']);

    Route::post('/players', [PostController::class, 'store']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::patch('/players/{id}', [PatchController::class, 'update']);

    Route::delete('/players/{id}', [DeleteController::class, 'destroy']);
});

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

Route::post('/auth/login', [AuthController::class, 'login']);
