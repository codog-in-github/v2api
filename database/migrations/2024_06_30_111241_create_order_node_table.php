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
            $table->boolean('is_enable')->default(0)->comment('是否启用');
            $table->tinyInteger('mail_status')->default(0)->comment('送信状态0否 是1');
            $table->boolean('is_top')->default(0)->comment('是否置顶0否 1是');
            $table->dateTime('top_at')->nullable()->comment('置顶时间');
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
