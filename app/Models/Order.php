<?php

namespace App\Models;

use App\Models\Filter\BaseFilter;
use App\Models\Filter\OrderFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'old_id', 'bkg_date', 'bkg_no', 'bl_no', 'bkg_type', 'month', 'month_no', 'tag', 'company_name', 'short_name', 'zip_code',
        'address', 'header', 'mobile', 'legal_number', 'carrier', 'c_staff', 'service', 'vessel_name', 'voyage', 'loading_country_id',
        'loading_country_name', 'loading_port_id', 'loading_port_name', 'etd', 'cy_open', 'cy_cut', 'doc_cut', 'delivery_country_id',
        'delivery_country_name', 'delivery_port_id', 'delivery_port_name', 'eta', 'free_time_dem', 'free_time_det', 'discharge_country',
        'discharge_port', 'remark', 'creator', 'custom_com_id', 'order_type', 'order_no', 'customer_id', 'is_top', 'status', 'finish_at'
    ];

    public function scopeFilter($query, BaseFilter $filters)
    {
        return $filters->apply($query);
    }

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
        do {
            $mothNo++;
        } while (preg_match($preg, $mothNo));
        $orderNo = date('Ym') . $mothNo . config('order')['tag'];
        return compact('orderNo', 'month', 'mothNo');
    }

    //获取预警颜色
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

    /**
     * 返回对应状态的节点id
     * @param $status
     * @return int|int[]|void
     */
    public static function getStatus($status)
    {
        switch ($status) {
            case 1:
                return [1, 2];
            case 2:
                return 3;
            case 3:
                return 4;
            case 4:
                return 5;
            case 5:
                return 6;
            case 6:
                return 7;
            case 7:
                return 8;
            case 8:
                return 10;
            case 9:
                return 11;
            default:
                return 0;
        }
    }
}
