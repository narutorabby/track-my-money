<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\MemberController;

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
        Route::get('personal', [RecordController::class, 'personal']);
        Route::get('group', [RecordController::class, 'group']);
        Route::get('show/{id}', [RecordController::class, 'show']);
        Route::post('create', [RecordController::class, 'store']);
        Route::put('update/{id}', [RecordController::class, 'update']);
        Route::delete('delete/{id}', [RecordController::class, 'delete']);
    });
    Route::prefix('group/')->group(function () {
        Route::get('list', [GroupController::class, 'index']);
        Route::get('show/{slug}', [GroupController::class, 'show']);
        Route::post('create', [GroupController::class, 'store']);
        Route::put('update/{id}', [GroupController::class, 'update']);
        Route::delete('delete/{id}', [GroupController::class, 'delete']);
        Route::post('invite', [GroupController::class, 'invite']);
        Route::post('cancel', [GroupController::class, 'cancelInvitation']);
        Route::post('decline', [GroupController::class, 'declineInvitation']);
    });
    Route::prefix('invitation/')->group(function () {
        Route::get('list', [InvitationController::class, 'list']);
        Route::post('invite', [InvitationController::class, 'invite']);
        Route::post('invitation-action/{id}', [InvitationController::class, 'invitationAction']);
    });
    Route::prefix('member/')->group(function () {
        Route::get('list', [MemberController::class, 'list']);
    });
});
