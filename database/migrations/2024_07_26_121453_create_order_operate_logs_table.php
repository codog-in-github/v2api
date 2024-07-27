<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderOperateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_operate_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('订单id');
            $table->tinyInteger('type')->comment('事件类型1发邮件 2节点确认');
            $table->text('content')->nullable()->comment('关键数据');
            $table->string('operator')->comment('操作人');
            $table->dateTime('operate_at')->comment('操作时间');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}order_operate_logs` comment '操作记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_operate_logs');
    }
}
