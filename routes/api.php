<?php

use Illuminate\Support\Facades\Route;

Route::any('/login', [\App\Http\Controllers\User::class, 'login']);

Route::middleware(\App\Http\Middleware\VerifyToken::class)->group(function () {
    Route::prefix('user')->group(function () {

    });
});
