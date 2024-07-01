<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderNodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_node', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('订单id');
            $table->integer('node_id')->comment('节点id');
            $table->tinyInteger('sort')->comment('节点顺序');
            $table->tinyInteger('status')->default(0)->comment('节点状态0未开始 1进行中 2已结束');
            $table->tinyInteger('mail_status')->default(0)->comment('送信状态0否 是1');
            $table->dateTime('start_time')->nullable()->comment('节点开始时间');
            $table->dateTime('end_time')->nullable()->comment('节点结束时间');
            $table->timestamps();

            $table->index('order_id');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}order_node` comment '订单节点表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_node');
    }
}
