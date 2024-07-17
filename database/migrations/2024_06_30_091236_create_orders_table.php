<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('old_id')->default('')->comment('老系统订单id');
            $table->tinyInteger('order_type')->default(1)->comment('订单类型 1出口 2进口');
            $table->date('bkg_date')->comment('下单日期');
            $table->string('bkg_no', 100)->default('')->comment('订单号');
            $table->string('bl_no')->default('')->comment('bl_no');
            $table->tinyInteger('bkg_type')->default(0)->comment('类型1-7为固定 8自定义');
            $table->string('month')->default('')->comment('下单月');
            $table->integer('month_no')->default(0)->comment('本月第几个订单');
            $table->string('tag')->default('')->comment('标签');
            $table->string('order_no')->default('')->comment('社内管理番号年月+本月第几单+标签');
            $table->string('custom_com_id')->default('')->comment('报关公司id');

//            $table->integer('node_id')->default(0)->comment('当前节点id');
//            $table->string('node_name')->default('')->comment('当前节点中文');
            //客户信息
            $table->integer('customer_id')->default(0)->comment('客户id');
            $table->string('company_name')->default('')->comment('名称');
            $table->string('short_name')->default('')->comment('简称');
            $table->string('zip_code')->default('')->comment('邮编');
            $table->string('email')->default('')->comment('邮编');
            $table->string('address')->default('')->comment('地址');
            $table->string('header')->default('')->comment('负责人');
            $table->string('mobile')->default('')->comment('联系方式');
            $table->string('legal_number')->default('')->comment('法人番号');
            //船社信息
            $table->string('carrier')->default('')->comment('船社');
            $table->string('c_staff')->default('')->comment('c_staff');
            $table->string('service')->default('')->comment('service');
            $table->string('vessel_name')->default('')->comment('船名');
            $table->string('voyage')->default('')->comment('航线');
            //loading 装船信息
            $table->integer('loading_country_id')->default(0)->comment('装船国家id');
            $table->string('loading_country_name')->default('')->comment('装船国家名');
            $table->integer('loading_port_id')->default(0)->comment('装船港口id');
            $table->string('loading_port_name')->default('')->comment('装船港口名');
            $table->date('etd')->nullable()->comment('预计交货时间');
            $table->date('cy_open')->nullable()->comment('开港日');
            $table->date('cy_cut')->nullable()->comment('截港日');
            $table->date('doc_cut')->nullable()->comment('文件结关时间');

            //delivery
            $table->integer('delivery_country_id')->default(0)->comment('抵达国家id');
            $table->string('delivery_country_name')->default('')->comment('抵达国家名');
            $table->integer('delivery_port_id')->default(0)->comment('抵达港口id');
            $table->string('delivery_port_name')->default('')->comment('抵达港口名');
            $table->date('eta')->nullable()->comment('预计到港时间');
            $table->string('free_time_dem')->default('')->comment('free_time_dem');
            $table->string('free_time_det')->default('')->comment('free_time_det');
            $table->string('discharge_country')->default('')->comment('卸货国家');
            $table->string('discharge_port')->default('')->comment('卸货港口');

            $table->string('remark')->default('')->comment('备注');
            $table->string('creator')->default('')->comment('创建人');

            $table->boolean('is_top')->default(0)->comment('是否置顶 0否 1是');

            $table->tinyInteger('status')->default(0)->comment('订单大状态0未开始 1进行中 2已完成 3订单终止');
            $table->date('finish_at')->nullable()->comment('完成或者终止时间');

            $table->string('assign_user')->default(0)->comment('任务指派人');
            $table->dateTime('assign_at')->nullable()->comment('指派时间');
            $table->string('task_user')->default(0)->comment('任务操作人');
            $table->dateTime('task_finish_at')->nullable()->comment('任务完成时间');
            $table->string('task_remark')->default('')->comment('任务备注');

            $table->tinyInteger('apply_num')->default(0)->comment('给会计的申请数量');

            $table->timestamps();

            $table->index('bkg_no');

            $table->softDeletes();
        });
        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}orders` comment '订单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
