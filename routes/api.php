<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RecordController;

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
    Route::prefix('record/')->group(function () {
        Route::get('list', [RecordController::class, 'list']);
        Route::get('details/{id}', [RecordController::class, 'show']);
        Route::post('create', [RecordController::class, 'store']);
        Route::put('update/{id}', [RecordController::class, 'update']);
        Route::delete('delete/{id}', [RecordController::class, 'delete']);
    });
});
