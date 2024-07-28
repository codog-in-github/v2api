<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderNodeTable0728 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_node', function (Blueprint $table) {
            $table->dateTime('top_finish_time')->nullable()->comment('置顶结束时间');
            $table->boolean('has_change_order')->default(0)->comment('是否改单');
            $table->tinyInteger('step')->default(0)->comment('节点步骤 目前只有SUR有 1申请付款 2发送付款凭证');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
