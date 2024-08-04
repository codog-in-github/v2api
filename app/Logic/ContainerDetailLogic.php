<?php

namespace App\Logic;
use App\Models\Bank;
use App\Models\ContainerDetail;
use App\Models\Order;

class ContainerDetailLogic extends Logic
{
    public static function list($request)
    {
        $nodeId = Order::getNodeId($request['node_status']);
        $query = ContainerDetail::query()->with('order')
            ->whereHas('order', function ($query)use($request){
                //订单大状态0未开始 1进行中 2已完成 3 订单终止
                if (is_numeric($request['status'])){
                    $query->where('status', $request['status']);
                }
            });
        if ($request['node_status']){
            $query->whereHas('nodes', function ($query)use($nodeId){
                if (is_array($nodeId)){
                    $query->whereIn('node_id', $nodeId);
                }else{
                    $query->where('node_id', $nodeId);
                }
                $query->where('is_enable', 1);
            });
        }
        $list = $query->orderBy('deliver_time')->paginate($request['page_size'] ?? 20);
        foreach ($list as $item){
            $item->loading_country_name = $item->order->loading_country_name; //装柜国家
            $item->loading_port_name = $item->order->loading_port_name; //装柜港口
            $item->delivery_country_name = $item->order->delivery_country_name; //到达国家
            $item->delivery_port_name = $item->order->delivery_port_name; //到达港口
            $item->company_name = $item->order->company_name; //到达港口
            $item->bkg_no = $item->order->bkg_no; //到达港口
            $item->color = ContainerDetail::getWarningColor($item['deliver_time']); //预警颜色
            unset($item['order']);
        }
        return $list;
    }
}
