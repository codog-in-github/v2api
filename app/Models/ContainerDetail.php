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

    public function nodes()
    {
        return $this->hasMany(OrderNode::class, 'order_id', 'order_id')->orderBy('sort');
    }

    /**
     * PO DRIVE节点根据交付时间获取预警颜色
     * @param $time
     * @return int
     */
    static public function getWarningColor($time, $nodeType = 1)
    {
        $diff = Carbon::now()->diffInDays($time);
        $warringTypes = Order::getWarningType($nodeType);
        for($i = 0; $i < count($warringTypes); $i++) {
            if ($diff <= $warringTypes[$i]){
                return $i;
            }
        }
        return count($warringTypes);
    }

}
