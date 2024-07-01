<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_details', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('订单id');
            $table->integer('container_id')->comment('集装箱id');
            $table->string('van_place')->default('')->comment('van_place');
            $table->tinyInteger('van_type')->default(1)->comment('van类型');
            $table->tinyInteger('bearing_type')->comment(1)->comment('轴承类型1 二轴 2三轴');
            $table->date('deliver_day')->nullable()->comment('交付日期');
            $table->string('deliver_time')->default('')->comment('交付时间 小时');
            $table->string('trans_com')->default('')->comment('运输公司');
            $table->string('driver')->default('')->comment('司机');
            $table->string('tel')->default('')->comment('联络方式');
            $table->string('car')->default('')->comment('车号');
            $table->string('container')->default('')->comment('集装箱');
            $table->string('sear')->default('')->comment('封装');
            $table->string('tare')->default('')->comment('重量');
            $table->tinyInteger('tare+type')->default(1)->comment('重量类型1 吨 2 kg');
            $table->timestamps();

            $table->index('order_id');
            $table->index('container_id');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}container_details` comment '集装箱详情表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_detail');
    }
}
