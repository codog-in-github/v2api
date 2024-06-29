<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin'
], function () {
    //后台登录
    Route::post('login', 'UserController@login')->name('login');

    Route::group([
        //需要登录的接口
        'middleware' => []
    ], function () {
        Route::post('upload_file', 'FileController@upload');
    });
});
