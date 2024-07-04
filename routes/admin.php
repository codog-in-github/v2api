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

        Route::get('bank_list', 'BankController@list');

        Route::group(['prefix' => 'user'], function (){
            Route::get('list', 'CountryController@list');
            Route::get('tree', 'CountryController@tree');
        });

        //管理员
        Route::group(['prefix' => 'user'], function (){
            Route::get('destroy', 'CountryController@destroy');
            Route::get('user_list', 'CountryController@userList');
        });
        //菜单权限
        Route::group(['prefix' => 'permission'], function (){
            Route::get('role_list', 'PermissionController@roleList');
            Route::post('save_role', 'PermissionController@saveRole');
            Route::post('del_role', 'PermissionController@delRole');
            Route::get('permission_list', 'PermissionController@permissionList');
            Route::post('role_bind_permission', 'PermissionController@roleBindPermission');
            Route::post('user_permission', 'PermissionController@userPermission');
        });
        //顾客
        Route::group(['prefix' => 'customer'], function (){
            Route::get('list', 'CustomerController@list');
            Route::post('save', 'CustomerController@save');
            Route::post('delete', 'CustomerController@delete');
        });
        //订单
        Route::group(['prefix' => 'order'], function (){
            Route::post('create', 'OrderController@createOrder');
            Route::get('detail', 'OrderController@detail');
            Route::post('edit_order', 'OrderController@editOrder');
            Route::post('delete', 'OrderController@delete');
            Route::post('save_file', 'OrderController@saveFile');
            Route::post('del_file', 'OrderController@delFile');
            Route::post('send_message', 'OrderController@sendMessage');
            Route::get('message_list', 'OrderController@messageList');
            Route::post('read_message', 'OrderController@readMessage');
            Route::get('get_custom_com', 'OrderController@getCustomCom');
        });
    });
});
