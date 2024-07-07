<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestBookCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_book_counts', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->comment('请求书id');
            $table->tinyInteger('type')->default(0)->comment('明细类型1通关 2海上 3运输 4其他');
            $table->string('item_name')->default('')->comment('项目名');
            $table->decimal('item_amount')->default(0)->comment('明细小计');
            $table->string('purchase')->default('')->comment('供应商(仕入先) 逗号拼接');
            $table->timestamps();

            $table->index('request_id');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}request_book_counts` comment '请求书明细小计表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_book_counts');
    }
}
