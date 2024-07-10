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
     * 节点状态
     * @param $nodeStatus
     * @return mixed
     */
    public function node_status($nodeStatus)
    {
        //todo 规则不一样 需要写在sql里
        //5通关资料 6ACL 7许可 8B/C 10SUR 11请求书
        switch ($nodeStatus){
            case 5:
            case 7:
                $column = 'cy_cut';
                $redDay = 1;
                $yellowDay = 2;
            case 6:
                $column = 'doc_cut';
                $redDay = 1;
                $yellowDay = 2;
            case 8:
                $column = 'cy_cut';
                $redDay = 0;
                $yellowDay = 1;
            case 10:
                $column = 'cy_cut';
                $redDay = 0;
                $yellowDay = 1;
        }

        $status = Order::getStatus($nodeStatus);
        $this->builder->whereHas('nodes', function ($query)use($status){
            if (is_array($status)){
                $query->whereIn('node_id', $status);
            }else{
                $query->where('node_id', $status);
            }
            $query->where('mail_status', '!=', 1);
        });
        return $this->builder;
    }
}
