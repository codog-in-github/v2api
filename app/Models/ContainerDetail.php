<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ContainerDetail extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * PO DRIVE节点根据交付时间获取预警颜色
     * @param $time
     * @return int
     */
    static public function getWarningColor($time)
    {
        $diff = Carbon::now()->diffInDays($time);
        if ($diff <= config('order')['loading_warning_days']['red']){
            return 1;
        }
        if ($diff <= config('order')['loading_warning_days']['yellow']){
            return 2;
        }
        return 3;
    }


}
