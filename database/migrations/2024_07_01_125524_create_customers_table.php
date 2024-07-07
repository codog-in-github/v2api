<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('')->comment('名称');
            $table->string('short_name')->default('')->comment('简称');
            $table->string('zip_code')->default('')->comment('邮编');
            $table->string('address')->default('')->comment('地址');
            $table->string('header')->default('')->comment('负责人');
            $table->string('mobile')->default('')->comment('联系方式');
            $table->string('legal_number')->default('')->comment('法人番号');
            $table->string('fax')->default('')->comment('传真');
            $table->timestamps();

            $table->softDeletes();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}customers` comment '顾客表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
