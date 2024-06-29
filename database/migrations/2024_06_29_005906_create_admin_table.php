<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('姓名');
            $table->string('password')->comment('密码');
            $table->string('username')->comment('用户名');
            $table->string('tag', 4)->comment('标签');
            $table->integer('role_id')->comment('角色id');
            $table->boolean('enable')->comment('是否可用0否 1是');
            $table->timestamps();
        });
        $prefix = DB::getConfig('admin');
        DB::statement("ALTER TABLE `{$prefix}admin` comment '管理员表'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
