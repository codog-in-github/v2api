<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RequestBookController;
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
            Route::get('me', 'UserController@me');
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
            Route::get('detail', 'CustomerController@detail');
            Route::post('save', 'CustomerController@save');
            Route::post('delete', 'CustomerController@delete');
        });
        //订单
        Route::group(['prefix' => 'order'], function (){
            Route::get('list', [OrderController::class, 'orderList']);
            Route::get('top_list', [OrderController::class, 'topOrderList']); //top页特殊订单列表
            Route::get('tab_order_list', [OrderController::class, 'tabOrderList']); //tab页特殊订单列表
            Route::post('create', [OrderController::class, 'createOrder']);
            Route::get('detail', [OrderController::class, 'detail']);
            Route::post('edit_order', [OrderController::class, 'editOrder']);
            Route::post('delete', [OrderController::class, 'delete']);
            Route::post('save_file', [OrderController::class, 'saveFile']); //保存附件
            Route::post('del_file', [OrderController::class, 'delFile']); //删除附件
            Route::post('send_message', [OrderController::class, 'sendMessage']); //留言
            Route::get('message_list', [OrderController::class, 'messageList']); //留言列表
            Route::post('read_message', [OrderController::class, 'readMessage']); //留言已读
            Route::get('get_custom_com', [OrderController::class, 'getCustomCom']); //报关公司列表
            Route::get('container_list', [OrderController::class, 'containerList']); //集装箱列表
            Route::get('list_by_calendar', [OrderController::class, 'getListByCalendar']); //订单按日历列表
            Route::get('list_by_ship', [OrderController::class, 'getListByShip']); //订单按日历列表
            Route::post('update_ship_schedule', [OrderController::class, 'updateShipSchedule']); //船期更新
            Route::post('send_email', [OrderController::class, 'sendEmail']); //批量发送邮件
            Route::get('email_log', [OrderController::class, 'emailLogs']); //批量发送邮件
            Route::post('change_node_status', [OrderController::class, 'changeNodeStatus']); //节点开关闭
            Route::post('change_top', [OrderController::class, 'changeTop']); //置顶
//            Route::post('copy_order', 'OrderController@copyOrder'); //类似事件
            Route::get('bkg_type_text', [OrderController::class, 'bkgTypeText']);//订单类型
            Route::get('un_read_message_num', [OrderController::class, 'unReadMessageNum']);//我的消息数量
            Route::get('node_confirm', [OrderController::class, 'nodeConfirm']);//订单确认
            Route::get('change_order_request', [OrderController::class, 'changeOrderRequest']);//改单申请
            Route::get('mail_template', [OrderController::class, 'mailTemplate']);//邮件模板
        });

        //请求书
        Route::group(['prefix' => 'request_book'], function (){
            Route::get('detail', [RequestBookController::class, 'detail']);
            Route::post('save', [RequestBookController::class, 'save']);
            Route::post('delete', [RequestBookController::class, 'delete']);
            Route::post('change_status', [RequestBookController::class, 'changeStatus']);
            Route::post('export', [RequestBookController::class, 'exportRequestBook']);
            Route::post('pdf_by_stream', [RequestBookController::class, 'pdfByStream']);
            Route::post('copy', [RequestBookController::class, 'copy']);
        });
    });
});
