<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestBookExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_book_extras', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->comment('请求书id');
            $table->string('column', 50)->comment('字段名');
            $table->string('value')->comment('内容');
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}request_book_extras` comment '请求书自定义内容表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_book_extras');
    }
}
