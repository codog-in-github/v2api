<?php

namespace App\Logic;
use App\Models\Bank;
use App\Models\ContainerDetail;
use App\Models\Order;

class ContainerDetailLogic extends Logic
{
    public static function list($request)
    {
        $query = ContainerDetail::query()->with('order')
            ->whereHas('order', function ($query)use($request){
                //订单大状态0未开始 1进行中 2已完成 3 订单终止
                if (is_numeric($request['status'])){
                    $query->where('status', $request['status']);
                }
            });
        $list = $query->orderBy('deliver_time')->paginate($request['page_size'] ?? 20);
        foreach ($list as $item){
            $item->loading_country_name = $item->order->loading_country_name; //装柜国家
            $item->loading_port_name = $item->order->loading_port_name; //装柜港口
            $item->delivery_country_name = $item->order->delivery_country_name; //到达国家
            $item->delivery_port_name = $item->order->delivery_port_name; //到达港口
            $item->color = ContainerDetail::getWarningColor($item['deliver_time']); //预警颜色
            unset($item['order']);
        }
        return $list;
    }
}
