<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_books', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('订单id');
            $table->tinyInteger('type')->default(1)->comment('请求书类型1普通 2立替金');
            $table->string('no')->default('')->comment('请求番号');
            $table->dateTime('date')->nullable()->comment('请求日');
            $table->string('zip_code')->default('')->comment('邮编');
            $table->string('company_name')->default('')->comment('公司名');
            $table->string('company_address')->default('')->comment('公司地址');
            $table->decimal('total_amount')->default(0)->comment('小计');
            $table->decimal('tax')->default(0)->comment('税费');
            $table->decimal('request_amount')->default(0)->comment('预计请求金');
            $table->string('bank')->default('')->comment('银行');
            $table->string('address')->default('')->comment('地址');
            $table->tinyInteger('is_stamp')->default(1)->comment('是否盖章0否 1是');
            $table->boolean('is_confirm')->default(0)->comment('是否已确认0否 1是');
            $table->boolean('has_export')->default(0)->comment('是否已导出0否 1是');
            $table->timestamps();

            $table->softDeletes();
            $table->index('order_id');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}request_books` comment '请求书表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_books');
    }
}
