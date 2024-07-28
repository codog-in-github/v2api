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
        'middleware' => ['jwt.auth']
    ], function () {
        Route::post('upload_file', 'FileController@upload');
        Route::post('delete_files', 'FileController@delete');

        Route::get('bank_list', 'BankController@list');
        Route::get('select_list', 'BankController@selectList');
        Route::get('option_list', 'BankController@optionList');
        Route::get('department_list', 'BankController@departmentList');

        Route::group(['prefix' => 'country'], function (){
            Route::get('list', 'CountryController@list');
            Route::get('tree', 'CountryController@tree');
        });

        //管理员
        Route::group(['prefix' => 'user'], function (){
            Route::get('destroy', 'UserController@destroy');
            Route::get('user_list', 'UserController@userList');
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
            Route::get('list', 'OrderController@orderList');
            Route::get('tab_order_list', 'OrderController@tabOrderList'); //tab页特殊订单列表
            Route::post('create', 'OrderController@createOrder');
            Route::get('detail', 'OrderController@detail');
            Route::post('edit_order', 'OrderController@editOrder');
            Route::post('delete', 'OrderController@delete');
            Route::post('save_file', 'OrderController@saveFile'); //保存附件
            Route::post('del_file', 'OrderController@delFile'); //删除附件
            Route::post('send_message', 'OrderController@sendMessage'); //留言
            Route::get('message_list', 'OrderController@messageList'); //留言列表
            Route::post('read_message', 'OrderController@readMessage'); //留言已读
            Route::get('get_custom_com', 'OrderController@getCustomCom'); //报关公司列表
            Route::get('container_list', 'OrderController@containerList'); //集装箱列表
            Route::get('list_by_calendar', 'OrderController@getListByCalendar'); //订单按日历列表
            Route::get('list_by_ship', 'OrderController@getListByShip'); //订单按日历列表
            Route::post('update_ship_schedule', 'OrderController@updateShipSchedule'); //船期更新
            Route::post('send_email', 'OrderController@sendEmail'); //批量发送邮件
            Route::post('change_node_status', 'OrderController@changeNodeStatus'); //节点开关闭
            Route::post('change_top', 'OrderController@changeTop'); //置顶
//            Route::post('copy_order', 'OrderController@copyOrder'); //类似事件
            Route::get('bkg_type_text', 'OrderController@bkgTypeText');//订单类型
            Route::get('un_read_message_num', 'OrderController@unReadMessageNum');//我的消息数量
            Route::get('node_confirm', 'OrderController@nodeConfirm');//订单确认
            Route::get('change_order_request', 'OrderController@changeOrderRequest');//改单申请
        });

        //请求书
        Route::group(['prefix' => 'request_book'], function (){
            Route::get('detail', 'RequestBookController@detail');
            Route::post('save', 'RequestBookController@save');
            Route::post('delete', 'RequestBookController@delete');
            Route::post('export', 'RequestBookController@exportRequestBook');
            Route::post('change_status', 'RequestBookController@changeStatus');
        });
    });
});
