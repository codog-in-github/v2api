<?php

namespace App\Logic;
use App\Exceptions\ErrorException;
use App\Models\Bank;
use App\Models\Container;
use App\Models\ContainerDetail;
use App\Models\Customer;
use App\Models\NodeConfig;
use App\Models\Order;
use App\Models\OrderNode;
use Illuminate\Support\Facades\DB;

class OrderLogic extends Logic
{
    public static function createOrder($request)
    {
        $customer = Customer::query()->find($request['customer_id']);
        $order = (new Order())->fill($customer->toArray());
        $num = Order::getMonthNo();
        $order->month = $num['month'];
        $order->month_no = $num['num'] + 1;
        $order->creator = auth('user')->user()->username;
        $order->save();
        if ($request['bkg_type']){
            self::createNode($order);
        }
        return $order;
    }

    public static function detail($id)
    {
        return Order::query()->with(['containers', 'nodes', 'files', 'messages'])->find($id);
    }

    public static function editOrder($request)
    {
        $order = Order::query()->findOrFail($request['id']);
        if ($order['bkg_type'] && $request['bkg_type'] && $request['bkg_type'] != $order['bkg_type']){
            throw new ErrorException('bkg_type変更不可');
        }
        DB::beginTransaction();
        try {
            $order->fill($request->all())->save();
            if ($order->isDirty('bkg_type')){
                self::createNode($order);
            }
            if ($request['containers']){
                $oldContainerIds = $order->containers->pluck('id')->toArray();
                $oldContainerDetailIds = $order->containerDetails->pluck('id')->toArray();
                $newContainerIds = array_column($request['containers'], 'id');
                $newContainerDetailIds = array_column(array_column($request['containers'], 'details'), 'id');
                //需要删除的旧数据
                $delContainerIds = array_diff($oldContainerIds, $newContainerIds);
                $delContainerDetailIds = array_diff($oldContainerDetailIds, $newContainerDetailIds);
                Container::query()->whereIn('id', $delContainerIds)->delete();
                ContainerDetail::query()->whereIn('id', $delContainerDetailIds)->delete();

                foreach ($request['containers'] as $container){
                    $con = Container::query()->updateOrCreate([
                        'id' => $container['id'] ?? 0
                    ],[
                        'order_id' => $order->id,
                        'common' => $container['common'],
                        'container_type' => $container['container_type'],
                    ]);
                    foreach ($container['details'] as $detail){
                        Container::query()->updateOrCreate([
                            'id' => $detail['id'] ?? 0
                        ],[
                            'order_id'      => $order->id,
                            'container_id'  => $con->id,
                            'van_place'     => $detail['van_place'],
                            'van_type'      => $detail['van_type'],
                            'bearing_type'  => $detail['bearing_type'],
                            'deliver_day'   => $detail['deliver_day'],
                            'deliver_time'  => $detail['deliver_time'],
                            'trans_com'     => $detail['trans_com'],
                            'driver'        => $detail['driver'],
                            'tel'           => $detail['tel'],
                            'car'           => $detail['car'],
                            'container'     => $detail['container'],
                            'sear'          => $detail['sear'],
                            'tare'          => $detail['tare'],
                            'tare_type'     => $detail['tare_type'],
                        ]);
                    }
                }
            }
            DB::commit();
            return $order;
        }catch(\Exception $e) {
            DB::rollBack();
            throw new ErrorException($e->getMessage());
        }

    }

    public static function delete($request)
    {
        return Order::query()->where('id', $request['id'])->delete();
    }

    public static function createNode($order)
    {
        if ($order->nodes->isEmpty()){
            if ($order['bkg_type'] == 8){
                $nodes = NodeConfig::query()->where('status', 1)->whereIn('node_ids', $order['node_ids'])->orderBy('sort')->get();
            }else{
                $nodes = NodeConfig::query()->where('status', 1)->whereIn('node_ids', config('node_config')[$order['bkg_type']])->orderBy('sort')->get();
            }
            $first = array_shift($nodes);
            $order->node_id = $first['id'];
            $order->node_name = $first['name'];
            $order->save();
            $insert = [];
            foreach ($nodes as $k => $v){
                $insert[] = [
                    'node_id'       => $v['id'],
                    'status'        => 0,
                    'order_id'      => $order['id'],
                    'sort'          => $k + 1,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
            }
            OrderNode::query()->insert($insert);
        }
    }

}
