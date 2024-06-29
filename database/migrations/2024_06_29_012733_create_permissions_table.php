<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id()->comment('权限ID');
            $table->unsignedInteger('pid')->default(0)->comment('父权限ID');
            $table->enum('type', ['M','C','F'])->comment('菜单类型（M目录 C菜单 F按钮）');
            $table->string('title', 50)->default('')->comment('权限名称(中文)');
            $table->string('perms', 50)->default('')->comment('权限唯一标识');
            $table->string('name', 50)->default('')->comment('路由名称');
            $table->string('path', 200)->default('')->comment('路由地址');
            $table->string('component', 200)->default('')->comment('组件路径');
            $table->tinyInteger('hidden')->comment('是否隐藏');
            $table->string('icon', 100)->default('#')->comment('图标');
            $table->integer('sort')->default(0)->comment('排序，越小越前');
            $table->unsignedInteger('operate_id')->default(0)->comment('操作者id');
            $table->timestamps();
            $table->softDeletes();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}permissions` comment '后台权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
