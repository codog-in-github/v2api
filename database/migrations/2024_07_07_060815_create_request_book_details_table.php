<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestBookDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_book_details', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->comment('请求书id');
            $table->tinyInteger('type')->default(0)->comment('明细类型1通关 2海上 3运输 4其他');
            $table->string('item_name')->default('')->comment('项目名');
            $table->string('detail')->default('')->comment('项目明细');
            $table->tinyInteger('currency')->default(1)->comment('币种1美元');
            $table->decimal('price')->default(0)->comment('单价');
            $table->integer('num')->default(1)->comment('数量');
            $table->string('unit')->default('')->comment('单位');
            $table->decimal('tax')->default(0)->comment('税费');
            $table->decimal('amount')->default(0)->comment('金额');
            $table->timestamps();

            $table->index('request_id');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}request_book_details` comment '请求书明细表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_book_details');
    }
}
