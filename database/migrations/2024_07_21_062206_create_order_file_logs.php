<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderFileLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_file_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_path', '255')->comment('文件路径');
            $table->integer('order_id')->comment('订单id');
            $table->tinyInteger('type')->comment('文件分类');
            $table->tinyInteger('opt_type')->comment('操作类型 1 上传 2 删除');
            $table->integer('user_id')->default(-1)->comment('用户id -1 表示系统');
            $table->string('user_name', '255')->default('')->comment('用户名称');
            $table->string('remark', '255')->default('')->comment('备注');
            $table->string('recycle_hash', '32')->default('')->comment('删除文件hash');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_file_logs');
    }
}
