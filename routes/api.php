<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('auth.')->prefix('/auth')->group(function() {

    Route::post('/register', [AuthController::class, "register"])->name('register');
    Route::post('/login', [AuthController::class, "login"])->name('login');
    Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, "logout"])->name('logout');

});

/**
 *
 */
Route::middleware('auth:sanctum')->resource('movies', MovieController::class)->except(['create', 'edit']);
