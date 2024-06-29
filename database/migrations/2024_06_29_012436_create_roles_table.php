<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id()->comment('角色ID');
            $table->string('name', 30)->default('')->comment('角色名称');
            $table->integer('sort')->default(0)->comment('排序，越小越前');
            $table->unsignedInteger('operate_id')->default(0)->comment('操作者id');
            $table->timestamps();
            $table->softDeletes();
            $table->index('name');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}roles` comment '后台角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
