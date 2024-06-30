<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNodeConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_config', function (Blueprint $table) {
            $table->id();
            $table->string('node_name')->comment('节点名');
            $table->unsignedInteger('sort')->comment('排序 小号在前');
            $table->tinyInteger('status')->comment('在用状态0否 1是');
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}node_config` comment '流程节点配置表'");

        \Illuminate\Support\Facades\DB::table('node_config')->insert([
            [
                'node_name' => 'BK',
                'sort' => 1,
                'status' => 1,
            ],
            [
                'node_name' => '運',
                'sort' => 2,
                'status' => 1,
            ],
            [
                'node_name' => 'PO',
                'sort' => 3,
                'status' => 1,
            ],
            [
                'node_name' => 'ド',
                'sort' => 4,
                'status' => 1,
            ],
            [
                'node_name' => '通',
                'sort' => 5,
                'status' => 1,
            ],
            [
                'node_name' => 'ACL',
                'sort' => 6,
                'status' => 1,
            ],
            [
                'node_name' => '許',
                'sort' => 7,
                'status' => 1,
            ],
            [
                'node_name' => 'B/C',
                'sort' => 8,
                'status' => 1,
            ],
            [
                'node_name' => 'FM',
                'sort' => 9,
                'status' => 1,
            ],
            [
                'node_name' => 'SUR',
                'sort' => 10,
                'status' => 1,
            ],
            [
                'node_name' => '請',
                'sort' => 11,
                'status' => 1,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_config');
    }
}
