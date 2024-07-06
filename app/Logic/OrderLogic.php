<?php

namespace App\Logic;
use App\Exceptions\ErrorException;
use App\Http\Resources\OrderResource;
use App\Models\Bank;
use App\Models\Container;
use App\Models\ContainerDetail;
use App\Models\CustomCompany;
use App\Models\Customer;
use App\Models\Filter\OrderFilter;
use App\Models\NodeConfig;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\OrderMessage;
use App\Models\OrderNode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderLogic extends Logic
{
    public static function orderList(Request $request)
    {
        $query = Order::query()->filter(new OrderFilter($request))->latest()->orderBy('is_top', 'desc');
        $list = $query->paginate($request['page_size'] ?? 20);
        foreach ($list as $value){
            $value->color = Order::getWarningColor($value['cy_cut']); //预警颜色
        }
        return $list;
    }

    public static function createOrder(Request $request)
    {
        $customer = Customer::query()->find($request['customer_id']);
        $order = (new Order())->fill($customer->toArray())->fill($request->only('bkg_type', 'remark'));

        $res = Order::createBkgNo();
        $order->order_no =  $res['orderNo'];
        $order->bkg_date = date('Y-m-d');
        $order->month = $res['month'];
        $order->month_no = $res['mothNo'];
        $order->creator = auth('user')->user()->username;
        DB::beginTransaction();
        try {
            $order->save();
            if ($request['bkg_type']) {
                self::createNode($order, $request['node_ids']);
            }
            DB::commit();
            return $order;
        }catch(\Exception $e){
            DB::rollBack();
            throw new  ErrorException($e->getMessage());
        }

    }

    public static function detail($id)
    {
        return Order::query()->with(['containers', 'nodes', 'files', 'messages'])->find($id);
    }

    public static function editOrder($request)
    {
        $order = Order::query()->findOrFail($request['id']);
//        if ($order['bkg_type'] && $request['bkg_type'] && $request['bkg_type'] != $order['bkg_type']) {
//            throw new ErrorException('bkg_type変更不可');
//        }
        DB::beginTransaction();
        try {
//            if ($order->isDirty('bkg_type')) {
            if (!$order['bkg_type'] &&  $request['bkg_type']) {
                self::createNode($order, $request['node_ids'] ?? []);
            }
            $order->fill($request->all())->save();
            if ($order->status == config('order')['order_status_un']){
                $order->status == config('order')['order_status_ing'];
            }
            //集装箱
            if ($request['containers']) {
                $oldContainerIds = $order->containers->pluck('id')->toArray();
                $oldContainerDetailIds = $order->containerDetails->pluck('id')->toArray();
                $newContainerIds = array_column($request['containers'], 'id');
                $newContainerDetailIds = array_column(array_column($request['containers'], 'details'), 'id');
                //需要删除的旧数据
                $delContainerIds = array_diff($oldContainerIds, $newContainerIds);
                $delContainerDetailIds = array_diff($oldContainerDetailIds, $newContainerDetailIds);
                Container::query()->whereIn('id', $delContainerIds)->delete();
                ContainerDetail::query()->whereIn('id', $delContainerDetailIds)->delete();

                foreach ($request['containers'] as $container) {
                    $con = Container::query()->updateOrCreate([
                        'id' => $container['id'] ?? 0
                    ], [
                        'order_id' => $order->id,
                        'common' => $container['common'],
                        'container_type' => $container['container_type'],
                    ]);
                    foreach ($container['details'] as $detail) {
                        Container::query()->updateOrCreate([
                            'id' => $detail['id'] ?? 0
                        ], [
                            'order_id' => $order->id,
                            'container_id' => $con->id,
                            'van_place' => $detail['van_place'],
                            'van_type' => $detail['van_type'],
                            'bearing_type' => $detail['bearing_type'],
                            'deliver_time' => $detail['deliver_time'],
                            'trans_com' => $detail['trans_com'],
                            'driver' => $detail['driver'],
                            'tel' => $detail['tel'],
                            'car' => $detail['car'],
                            'container' => $detail['container'],
                            'sear' => $detail['sear'],
                            'tare' => $detail['tare'],
                            'tare_type' => $detail['tare_type'],
                        ]);
                    }
                }
            }
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ErrorException($e->getMessage());
        }

    }

    public static function delete($request)
    {
        return Order::query()->where('id', $request['id'])->delete();
    }

    public static function createNode($order, $node_ids = [])
    {
        if ($order->nodes->isEmpty()) {
            if ($order['bkg_type'] == 8) {
                $nodes = NodeConfig::query()->where('status', 1)->whereIn('id', $node_ids)->orderBy('sort')->get()->toArray();
            } else {
                $nodes = NodeConfig::query()->where('status', 1)->whereIn('id', config('node_config')[$order['bkg_type']])->orderBy('sort')->get()->toArray();
            }

            $first = array_shift($nodes);
            $order->node_id = $first['id'];
            $order->node_name = $first['node_name'];
            $order->save();
            $insert = [];
            foreach ($nodes as $k => $v) {
                $insert[] = [
                    'node_id' => $v['id'],
                    'status' => 0,
                    'order_id' => $order['id'],
                    'sort' => $k + 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
            OrderNode::query()->insert($insert);
        }
    }

    public static function saveFile($request)
    {
        return OrderFile::query()->create([
            'order_id' => $request['order_id'],
            'file_path' => $request['file_path'],
            'type' => $request['type'],
        ]);
    }

    public static function delFile($request)
    {
        $file = OrderFile::query()->find($request['id']);
        @unlink(public_path($file->file_path));
        $file->delete();
        return 'success';
    }

    public static function sendMessage($request)
    {
        $sender = auth('user')->user();
        $receiver = User::query()->where('enable', 1)->find($request['receive_id']);
        if (!$sender || !$receiver) {
            throw new ErrorException('受信者は存在しません');
        }
        return OrderMessage::query()->create([
            'order_id' => $request['order_id'],
            'content' => $request['content'],
            'send_id' => $sender['id'],
            'sender' => $sender['username'],
            'receive_id' => $receiver['id'],
            'receiver' => $receiver['username'],
            'is_read' => 0,
        ]);
    }

    public static function messageList($request)
    {
        $query = OrderMessage::query()->latest();
        if ($request['at_me']){
            $query->where('receive_id', auth('user')->user()->id);
        }
        $list = $query->get();
        foreach ($list as $value){
            $value->at_me = $value->receive_id == auth('user')->user()->id ? 1 : 0;
        }
        return $list;
    }

    public static function readMessage($request)
    {
        return OrderMessage::query()->where('id', $request['id'])->update(['is_read' => 1]);
    }


    /**
     * 报关公司列表
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getCustomCom($request)
    {
        return CustomCompany::query()->get();
    }

    /**
     * 订单列表 按日历
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getListByCalendar($request)
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::FRIDAY);
        switch ($request['type']){
            case 1 :
                $start = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d H:i:s');
                $end = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d H:i:s');
                break;
            case 3 :
                $start = Carbon::now()->addWeek(1)->startOfWeek()->format('Y-m-d H:i:s');
                $end = Carbon::now()->addWeek(1)->endOfWeek()->format('Y-m-d H:i:s');
                break;
            default :
                $start = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
                $end = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
                break;
        }
        $query = Order::query()
            ->withCount('containers')
            ->whereBetween('cy_cut', [$start, $end])
            ->select(['id', 'cy_cut', 'bkg_type', 'company_name', 'short_name', 'loading_country_name',
                'loading_port_name', 'delivery_country_name', 'delivery_port_name']);
        if ($request['bkg_type']){
            $query->where('bkg_type', $request['bkg_type']);
        }
        return $query->get()->groupBy('cy_cut');
    }

    /**
     * 订单列表按船社分组
     * @param Request $request
     * @return array
     */
    public static function getListByShip(Request $request)
    {
        $request->offsetSet('status', 1);
        $query = Order::query()->filter(new OrderFilter($request))->latest();
        $group = $query->get()->groupBy('carrier');
        $data = [];
        foreach ($group as $item){
            $arr = [];
            foreach ($item as $value){
                $key = $value['carrier'] . $value['vessel_name'] . $value['voyage'];
                $arr[$key] = $value;
            }
            $data[] = array_values($arr);
        }
        return $data;
    }
}
