<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class,'login']);

Route::middleware(['jwt.verify'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/get_users',[AuthController::class,'get_users']);
        Route::post('/save_user', [AuthController::class,'save_user']);
        Route::get('/edit_user/{id}', [AuthController::class,'edit']);
        Route::post('/update_user', [AuthController::class,'update']);
        Route::delete('/delete_user', [AuthController::class,'delete']);
    });
});
