<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\GroupController;

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

Route::post('authenticate', [UserController::class, 'authenticate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user/')->group(function () {
        Route::get('me', [UserController::class, 'profile']);
        Route::put('update', [UserController::class, 'update']);
    });
    Route::prefix('record/')->group(function () {
        Route::get('list', [RecordController::class, 'index']);
        Route::get('details/{id}', [RecordController::class, 'show']);
        Route::post('create', [RecordController::class, 'store']);
        Route::put('update/{id}', [RecordController::class, 'update']);
        Route::delete('delete/{id}', [RecordController::class, 'delete']);
    });
    Route::prefix('group/')->group(function () {
        Route::get('list', [GroupController::class, 'index']);
        Route::get('details/{id}', [GroupController::class, 'show']);
        Route::post('create', [GroupController::class, 'store']);
        Route::put('update/{id}', [GroupController::class, 'update']);
        Route::delete('delete/{id}', [GroupController::class, 'delete']);
    });
});
