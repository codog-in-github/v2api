<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailAtToOrderNodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_node', function (Blueprint $table) {
            $table->dateTime('mail_at')->nullable()->after('mail_status')->comment('送信时间');
            $table->string('sender')->nullable()->after('mail_at')->comment('送信人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_node', function (Blueprint $table) {
            //
        });
    }
}
