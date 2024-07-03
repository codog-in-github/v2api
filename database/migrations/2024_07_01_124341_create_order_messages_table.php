<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('订单id');
            $table->string('content')->comment('内容');
            $table->integer('send_id')->comment('发送人id');
            $table->integer('sender')->comment('发送人姓名');
            $table->integer('receive_id')->comment('接收人id');
            $table->string('receiver')->comment('接收人姓名');
            $table->tinyInteger('is_read')->default(0)->comment('是否已读0否 1是');
            $table->timestamps();

            $table->index('order_id');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}order_messages` comment '订单消息留言表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_messages');
    }
}
