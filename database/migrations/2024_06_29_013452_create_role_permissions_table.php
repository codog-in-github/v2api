<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('role_id')->default(0)->comment('角色id');
            $table->unsignedInteger('permission_id')->default(0)->comment('权限id');
            $table->index(['role_id', 'permission_id']);
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}role_permissions` comment '后台角色对应权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permissions');
    }
}
