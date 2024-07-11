<?php
namespace App\Models\Filter;
use App\Models\Order;
use Carbon\Carbon;

class OrderFilter extends BaseFilter
{
    public function is_top($isTop)
    {
        return $this->builder->where('is_top', $isTop);
    }
    //顾客id
    public function customer_id($customerId)
    {
        return $this->builder->where('customer_id', $customerId);
    }
    //订单大状态0 未开始 1 进行中 2 已完成 3 订单终止
    public function status($status)
    {
        return $this->builder->where('status', $status);
    }
    //pol 出发港
    public function loading_country_id($loadingCountryId)
    {
        return $this->builder->where('loading_country_id', $loadingCountryId);
    }
    //pol 出发港
    public function delivery_country_id($deliveryCountryId)
    {
        return $this->builder->where('delivery_country_id', $deliveryCountryId);
    }
    //客样名
    public function company_name($companyName)
    {
        return $this->builder->where('company_name', 'like', "%{$companyName}%");
    }
    //番号
    public function order_no($orderNo)
    {
        return $this->builder->where('order_no', 'like', "%{$orderNo}%");
    }
    //番号
    public function bkg_no($bkgNo)
    {
        return $this->builder->where('bkg_no', 'like', "%{$bkgNo}%");
    }
    //blno
    public function bl_no($bl_no)
    {
        return $this->builder->where('bl_no', 'like', "%{$bl_no}%");
    }

    //船社
    public function carrier($carrier)
    {
        return $this->builder->where('carrier', 'like', "%{$carrier}%");
    }
    //船名
    public function vessel_name($vesselName)
    {
        return $this->builder->where('vessel_name', 'like', "%{$vesselName}%");
    }
    //航线
    public function voyage($voyage)
    {
        return $this->builder->where('voyage', 'like', "%{$voyage}%");
    }

    /**
     * 节点id
     * @param $nodeStatus
     * @return mixed
     */
    public function node_status($nodeStatus)
    {
        $nodeId = Order::getNodeId($nodeStatus);
        $this->builder->whereHas('nodes', function ($query)use($nodeId){
            if (is_array($nodeId)){
                $query->whereIn('node_id', $nodeId);
            }else{
                $query->where('node_id', $nodeId);
            }
            $query->where('is_enable', 1)->where('mail_status', '!=', 1);
        });
        //
        if (in_array($nodeStatus, [8,9])){
            $this->builder->orderBy('cy_cut', 'desc');
        }
        return $this->builder;
    }
}
