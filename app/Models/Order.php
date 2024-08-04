<?php

namespace App\Models;

use App\Enum\CodeEnum;
use App\Models\Filter\BaseFilter;
use App\Models\Filter\OrderFilter;
use App\Utils\HolidayJp\Calendar;
use App\Utils\Order\OrderNum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'old_id', 'bkg_date', 'bkg_no', 'bl_no', 'bkg_type', 'month', 'month_no', 'tag', 'company_name', 'short_name',
        'zip_code', 'address', 'header', 'mobile', 'legal_number', 'carrier', 'c_staff', 'service', 'vessel_name', 'voyage',
        'loading_country_id', 'loading_country_name', 'loading_port_id', 'loading_port_name', 'etd', 'cy_open', 'cy_cut',
        'doc_cut', 'delivery_country_id', 'delivery_country_name', 'delivery_port_id', 'delivery_port_name', 'eta',
        'free_time_dem', 'free_time_det', 'discharge_country', 'discharge_port', 'remark', 'creator', 'custom_com_id',
        'order_type', 'order_no', 'customer_id', 'is_top', 'status', 'finish_at', 'email', 'carrier_id'
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
        return $this->hasMany(OrderMessage::class, 'order_id', 'id');
    }

    public function carrier()
    {
        return $this->belongsTo(Option::class, 'carrier_id', 'id');
    }

    public function customCom()
    {
        return $this->belongsTo(CustomCompany::class, 'custom_com_id', 'id');
    }

    public function requestBooks()
    {
        return $this->hasMany(RequestBook::class, 'order_id', 'id')->latest();
    }
    public static function createBkgNo()
    {
        $month = date('m');
        $mothNo = self::query()->where('month', $month)->latest()->value('month_no') ?? 99;
        $orderNum = new OrderNum($mothNo);
        $newNum = $orderNum->next()->toInt();
        $start = auth('user')->user()->department ? auth('user')->user()->department . '-' : '';
        $orderNo = $start . date('Ym') . $newNum . config('order')['tag'];
        return compact('orderNo', 'month', 'mothNo');
    }

    //获取预警颜色 3 红色 2黄色 1绿色
    public static function getColor($nodeStatus, $order)
    {
        //3DRIVE 4通关资料 5ACL 6许可 7 B/C 8SUR 9请求书
        $start = Carbon::now();
        $end = Carbon::parse($order->cy_cut);
        $diffDays = self::compareTwoTime($start, $end);
        return self::getWarningColor($diffDays, $nodeStatus);
    }

    static public function getWarningColor($diffTime, $nodeType = 1)
    {
        $warringTypes = Order::getWarningType($nodeType);
        for($i = 0; $i < count($warringTypes); $i++) {
            if ($diffTime <= $warringTypes[$i]){
                return $i;
            }
        }
        return count($warringTypes);
    }


    /**
     * 开始时间与结束时间对比 跳过周末节假期
     * @param Carbon $start
     * @param Carbon $end
     * @return int
     */
    public static function compareTwoTime(Carbon $start, Carbon $end) :int
    {
        $iniDate = clone ($start);
        $holidays = Calendar::getHolidays($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'));
        $restDay = 0;
        while($start->lte($end)){
            if (in_array($start->dayOfWeek, [6, 0])){
                //周末+1天
                $restDay++;
            }
            if (in_array($start->format('Y-m-d'), $holidays)){
                $restDay++;
            }
            $start->addDay();
        }
        return $iniDate->addDays($restDay)->diffInDays($end);
    }

    /**
     * 返回对应状态的节点id
     * @param $nodeStatus //对应页面的9个tab页
     * @return int|int[]
     */
    public static function getNodeId($nodeStatus)
    {
        switch ($nodeStatus) {
            case 1:
                return [1, 2];
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
             return $nodeStatus + 1;
            default:
                throw new \Exception('参数错误');
        }
    }

    public static function getWarningType($ndeStatus)
    {
        switch ($ndeStatus) {
            case 1:
                return [2, 3];
            case 2:
            case 3:
            case 4:
            case 5:
                return [1, 2];
            case 6:
                return [-1, 0];
            default:
                return [];
        }
    }
}
