<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'old_id', 'bkg_date', 'bkg_no', 'bl_no', 'bkg_type', 'month', 'month_no', 'tag', 'company_name', 'short_name', 'zip_code',
        'address', 'header', 'mobile', 'legal_number', 'carrier', 'c_staff', 'service', 'vessel_name', 'voyage', 'loading_country_id',
        'loading_country_name', 'loading_port_id', 'loading_port_name', 'etd', 'cy_open', 'cy_cut', 'doc_cut', 'delivery_country_id',
        'delivery_country_name', 'delivery_port_id', 'delivery_port_name', 'eta', 'free_time_dem', 'free_time_det', 'discharge_country',
        'discharge_port', 'remark', 'creator', 'custom_com_id'
    ];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function containers()
    {
        return $this->hasMany(Container::class, 'order_id', 'id');
    }
    public function containerDetails()
    {
        return $this->hasMany(ContainerDetail::class, 'order_id', 'id');
    }

    public function nodes()
    {
        return $this->hasMany(OrderNode::class, 'order_id', 'id')->orderBy('sort');
    }

    public function files()
    {
        return $this->hasMany(OrderFile::class, 'order_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class, 'order_id', 'id')->latest();
    }

    public static function createBkgNo()
    {
        $month = date('m');
        $mothNo = self::query()->where('month', '07')->latest()->value('month_no') ?? 99;
        $preg = "/[349]|(?:001)/";
        do{
            $mothNo++;
        } while(preg_match($preg, $mothNo));
        $orderNo = date('Ym') . $mothNo . config('order')['tag'];
        return compact('orderNo', 'month', 'mothNo');
    }
}
