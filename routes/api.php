<?php

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

Route::any('/login', [\App\Http\Controllers\User::class, 'login']);

Route::middleware(\App\Http\Middleware\VerifyToken::class)->group(function () {
    Route::prefix('user')->group(function () {

    });
});
