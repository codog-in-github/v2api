<?php

namespace App\Logic;
use App\Enum\CodeEnum;
use App\Enum\OrderEnum;
use App\Exceptions\ErrorException;
use App\Http\Resources\OrderResource;
use App\Mail\MailCustom;
use App\Mail\OrderNodeMail;
use App\Models\Bank;
use App\Models\Container;
use App\Models\ContainerDetail;
use App\Models\CustomCompany;
use App\Models\Customer;
use App\Models\Filter\OrderFilter;
use App\Models\MailTemplate;
use App\Models\NodeConfig;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\OrderMessage;
use App\Models\OrderNode;
use App\Models\OrderOperateLog;
use App\Models\RequestBook;
use App\Models\User;
use App\Utils\Order\OrderFiles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use League\Flysystem\Config;

class OrderLogic extends Logic
{
    public static function tabOrderList(Request $request)
    {
        if (!is_numeric($request['status'])){
            $request->offsetSet('status', OrderEnum::STATUS_ING);
        }
        $query = Order::query()->filter(new OrderFilter($request))->with(['requestBooks', 'nodes'])
            ->select('id', 'order_type', 'bkg_no', 'order_no', 'cy_cut', 'doc_cut', 'loading_country_name', 'loading_port_name', 'company_name',
                'delivery_country_name', 'delivery_port_name', 'remark', 'status', 'apply_num', 'voyage', 'vessel_name', 'carrier', 'customer_id');
        if ($request['status'] == OrderEnum::STATUS_ING){
            $list = $query->latest()->get();
            if ($request['node_status'] && in_array($request['node_status'], [4,5,6,7])){
                foreach ($list as $value){
                    $value['color'] = Order::getColor($request['node_status'], $value);
                }
                //排序
                $colors = array_column($list, 'color');
                array_multisort($colors, SORT_DESC, $list);
            }
            if ($request['node_status'] && $request['node_status'] == 8) {
                foreach ($list as $value){
                    $value['color'] = $value->apply_num > 0 ? CodeEnum::COLOR_RED : CodeEnum::COLOR_GREEN;
                }
                //排序
                $colors = array_column($list, 'color');
                array_multisort($colors, SORT_DESC, $list);
            }
            if ($request['node_status'] && $request['node_status'] == 9) {
                foreach ($list as $value){
                    $value['color'] = $value->requestBooks->where('is_confirm', 0)->count() > 0 ? CodeEnum::COLOR_RED : CodeEnum::COLOR_GREEN ;
                }
                //排序
                $colors = array_column($list, 'color');
                array_multisort($colors, SORT_DESC, $list);
            }
            if (in_array($request['node_status'], [4,5,6,7,8,9])){
                $topArr = [];
                $nonTop = [];
                $nodeId = Order::getNodeId($request['node_status']);
                foreach ($list as $value){
                    $node = $value->nodes->where('node_id', $nodeId)->first();
                    if ($node && $node->is_top){
                        //加入置顶
                        $value->top_at = $node->top_at;
                        $topArr[] = $value;
                    }else{
                        $nonTop[] = $value;
                    }
                }
                $list = array_merge($topArr, $nonTop);

            }
        }else{
            $list = $query->latest()->limit(10)->get();
        }

        return $list;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public static function orderList(Request $request)
    {
        $list = Order::query()->filter(new OrderFilter($request))->with('requestBooks')
            ->select('id', 'order_type', 'bkg_no', 'order_no', 'cy_cut', 'doc_cut', 'loading_country_name', 'loading_port_name', 'company_name',
                'delivery_country_name', 'delivery_port_name', 'remark', 'status', 'apply_num', 'voyage', 'vessel_name', 'carrier', 'customer_id', 'created_at')
            ->latest()->paginate($request['page_size'] ?? 10);
        //todo
        return $list;
    }

    public static function createOrder(Request $request)
    {
        $customer = Customer::query()->find($request['customer_id']);
        $order = (new Order())->fill($customer->toArray() ?? [])->fill($request->only('bkg_type', 'remark'));

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
                $order->bkg_type_text = OrderEnum::BKG_TYPE_TEXT_ARR[$request['bkg_type']] ?? '';
                self::createNode($order, $request['node_ids']);
            }
            DB::commit();
            return $order;
        }catch(\Exception $e){
            DB::rollBack();
            throw new  ErrorException($e->getMessage());
        }

    }

    public static function detail($request)
    {
        $query = Order::query()
            ->with(['containers', 'containers.details', 'nodes', 'messages', 'requestBooks', 'carrier']);

        if ($request['id']){
            $orderDetail = $query->find($request['id']);
        }else{
            $keyword = $request['keyword'];
            $orderDetail = $query->where(function ($query)use($keyword){
                if (is_numeric($keyword)) {
                    $query->where('id', $keyword);
                } elseif(preg_match("/^(:?[123]-)?\d+K$/", $keyword)) {
                    $query->where('order_no', $keyword);
                } else {
                    $query->where('bkg_no', $keyword);
                }
            })->first();
        }
        $orderDetail = $orderDetail->toArray();
        $orderDetail['files'] = OrderFiles::getInstance()->getAsHttpURI($orderDetail['id'], true);
        return $orderDetail;
    }

    public static function editOrder($request)
    {
        $order = Order::query()->findOrNew($request['id'] ?? 0);
//        if ($order['bkg_type'] && $request['bkg_type'] && $request['bkg_type'] != $order['bkg_type']) {
//            throw new ErrorException('bkg_type変更不可');
//        }
        DB::beginTransaction();
        try {
            $order->fill($request->all());
            if ($order->isDirty('bkg_type') && $order->getOriginal('bkg_type') == 0) {
                $order->bkg_type_text = OrderEnum::BKG_TYPE_TEXT_ARR[$request['bkg_type']] ?? '';
                self::createNode($order, $request['node_ids'] ?? []);
            }
            $order->save();
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

                self::saveContainers($order, $request['containers']);
            }
            //如果有origin_order_id 需要复制请求书
            if ($request['origin_order_id']){
                $order = self::copyOrder($order, $request['origin_order_id']);
            }
            DB::commit();
//            $order = Order::query()->with(['nodes', 'containers','containers.details'])->find($order['id']);
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
            $allNodes = NodeConfig::query()->where('status', 1)->orderBy('sort')->get();
//            if ($order['bkg_type'] == 8) {
//                $nodes = NodeConfig::query()->where('status', 1)->whereIn('id', $node_ids)->orderBy('sort')->get()->toArray();
//            } else {
//                $nodes = NodeConfig::query()->where('status', 1)->whereIn('id', config('node_config')[$order['bkg_type']])->orderBy('sort')->get()->toArray();
//            }
            $node_ids = $order['bkg_type'] == 8 ? $node_ids : config('node_config')[$order['bkg_type']];

            $insert = [];
            foreach ($allNodes as $k => $v) {
                $insert[] = [
                    'node_id' => $v['id'],
                    'order_id' => $order['id'],
                    'sort' => $v['sort'],
                    'is_enable' => in_array($v['id'], $node_ids),
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
        if (!$sender) {
            throw new ErrorException('受信者は存在しません');
        }
        $messageData = [
            'order_id' => $request['order_id'],
            'content' => $request['content'],
            'send_id' => $sender['id'],
            'sender' => $sender['name'],
            'is_read' => 0,
        ];
        if($request['receive_id']) {
            $receiver = User::query()->where('enable', 1)->find($request['receive_id']);
            if(!$receiver) {
                throw new ErrorException('受信者は存在しません');
            }
            $messageData['receive_id'] = $receiver['id'];
            $messageData['receiver'] = $receiver['name'];
        }
        return OrderMessage::query()->create($messageData);
    }

    public static function messageList($request)
    {
        $query = OrderMessage::query()->latest();
        if ($request['at_me']){
            $query->where('receive_id', auth('user')->user()->id);
        }
        if ($request['min_id']){
            $query->where('id', '>', $request['min_id']);
        }
        if ($request['max_id']){
            $query->where('id', '<', $request['max_id']);
        }
        if ($request['page_size']){
            $list = $query->paginate($request['page_size'] ?? 10);
        }else{
            $list = $query->get();
        }
        foreach ($list as $value){
            $value->at_me = $value->receive_id == auth('user')->user()->id ? 1 : 0;
        }
        return $list;
    }

    /**
     * 消息数量
     * @param $receiver //接收人
     * @param $is_read //是否已读
     * @return int
     */
    public static function unReadMessageNum($receiver, bool $is_read = false) :int
    {
        return OrderMessage::query()->where([
            'receiver'  => $receiver,
            'is_read'   => $is_read,
        ])->count();
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
        switch ($request['type']){
            case 1 :
                $start = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d H:i:s');
                $end = Carbon::now()->subWeek()->startOfWeek()->addDays(4)->format('Y-m-d H:i:s');
                break;
            case 3 :
                $start = Carbon::now()->addWeek(1)->startOfWeek()->format('Y-m-d H:i:s');
                $end = Carbon::now()->addWeek(1)->startOfWeek()->addDays(4)->format('Y-m-d H:i:s');
                break;
            default :
                $start = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
                $end = Carbon::now()->startOfWeek()->addDays(4)->format('Y-m-d H:i:s');
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

    /**
     * 船期更新
     * @param $request
     * @return int
     */
    public static function updateShipSchedule($request)
    {
        return Order::query()->whereIn('id', $request['ids'])->update([
            'etd' => Carbon::parse($request['etd'])->format('Y-m-d H:i:s'),
            'eta' => Carbon::parse($request['eta'])->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * 节点完成邮件发送
     * @param $request
     * @return string
     */
    public static function sendEmail($request)
    {
        $nodeId = $request['node_id'];
        if(!$nodeId) {
            throw new \Exception('节点ID不能为空');
        }
        $node = OrderNode::findOrFail($nodeId);
        $order = Order::findOrFail($node->order_id);
        $updateData = [
            'mail_status'  => 1,
            'mail_at'      => date('Y-m-d H:i:s'),
            'sender'       => auth('user')->user()->name,
            'step'         => $node->step += 1,
        ];
        if(
            in_array($node->node_id, [
                OrderNode::TYPE_BK,
                OrderNode::TYPE_TRANSPORT_COMPANY,
                OrderNode::TYPE_PO,
                OrderNode::TYPE_DRIVER_NOTIFICATION,
                OrderNode::TYPE_CUSTOMER_DOCUMENTS
            ])
        ) {
            $updateData['is_confirm'] = 1;
        } elseif ($node->node_id == OrderNode::TYPE_REQUEST) {
            // todo 发送邮件文件是否是已导出的请求书 全部已发送 结束节点
        }
        $node->update($updateData);
        $node->save();
        $user = auth('user')->user();
        // 邮件发送
        $nodeOptData = [
            'to' => is_array($request['to']) ? $request['to'] : explode(',', $request['to']),
            'user' => $user->name,
            'user_id' => $user->id,
            'context' => $request['content'] ?? '',
            'files' => $request['files'] ?? [],
            'order_id' => $order->id,
            'node_id' => $node->node_id,
            'subject' => $request['subject']
        ];
        $mail = new OrderNodeMail($nodeOptData['to'], $nodeOptData['subject'], $nodeOptData['context'], $nodeOptData['files']);
        OrderOperateLog::writeLog(
            $node->order_id,
            $node->id,
            OrderOperateLog::TYPE_MAIL,
            json_encode($nodeOptData)
        );
//        Mail::mailer($mail->mailer)->send($mail);
        // 节点操作日志
        return 'success';
    }


    public static function emailLogs($request)
    {
        return OrderOperateLog::query()->where('node_id', '=', $request['id'])->get();
    }


    public static function changeNodeStatus($request)
    {
        $orderNode = OrderNode::query()->findOrFail($request['id']);
        // 进行中或者完成的节点状态不能修改
        if($orderNode->mail_status) {
            throw new \Exception('コンシステント状態');
        }
        if ($orderNode->node_id == 11){
            //todo 如果是 请 这个节点 需要向会计发起申请

            return $orderNode;
        }
        $orderNode->is_enable = $request['is_enable'];
        $orderNode->save();
        return $orderNode;
    }
    //置顶 取消置顶
    public static function changeTop($request)
    {
        $orderNode = OrderNode::query()->findOrFail($request['id']);
        if ($orderNode->is_top == $request['is_top']){
            throw new ErrorException('コンシステント状態');
        }
        $orderNode->is_top = $request['is_top'];
        if ($request['is_top']){
            $orderNode->top_at = date('Y-m-d H:i:s');
            $orderNode->top_finish_at = date('Y-m-d H:i:s', time() + 60 * 60);
        }
        $orderNode->save();
        $content = json_encode($request->all());
        OrderOperateLog::writeLog(
            $orderNode->order_id,
            $orderNode->id,
            OrderOperateLog::TYPE_NODE_TOP,
            $content
        );
        return $orderNode;
    }

    public static function copyOrder($order, $origin_order_id)
    {
        $originOrder = Order::query()
            ->with(['files', 'requestBooks'])
            ->find($origin_order_id)->toArray();
        if (!$originOrder){
            throw new ErrorException('元のオーダーは存在しません');
        }

//        self::saveContainers($order, $originOrder['containers']);

        $unset = function ($array)
        {
            unset($array['id']);
            unset($array['request_id']);
            unset($array['created_at']);
            unset($array['updated_at']);
            return $array;
        };
        foreach ($originOrder['requestBooks'] as $originBook){
            $book = new RequestBook();
            $book->fill($originBook);
            if ($originBook['file_path']){
                //copyfile
                $filePath = RequestBookLogic::getPdfName($book)['filePath'];
                copy(public_path($originBook['file_path']), public_path($filePath));
                $book['file_path'] = $filePath;
            }
            $book->save();

            $details = array_map($unset, $originBook['details']);
            $counts = array_map($unset, $originBook['counts']);
            $extras = array_map($unset, $originBook['extras']);

            $book->details()->createMany($details);
            $book->counts()->createMany($counts);
            $book->extras()->createMany($extras);
        }
        return $order;
    }

    public static function nodeConfirm($request)
    {
        $node = OrderNode::query()->find($request['id']);
        if (!$node){
            throw new ErrorException('ノードが存在しません');
        }
        if (!$node->is_enable){
            throw new ErrorException('ノードが閉じました');
        }
        $node->is_confirm = $request['is_confirm'];
        $node->is_enable = 0;
        $node->save();
        $content = json_encode($request->all());
        OrderOperateLog::writeLog(
            $node->order_id,
            $node->id,
            OrderOperateLog::TYPE_NODE_CONFIRM,
            $content
        );
        return $node->fresh();
    }

    //改单申请
    public static function changeOrderRequest($request)
    {
        $node = OrderNode::query()->find($request['id']);
        if (!$node){
            throw new ErrorException('ノードが存在しません');
        }
        if (!$node->is_confirm){
            throw new ErrorException('未確認、修正の必要なし');
        }
        $content = json_encode($request->all());
        OrderOperateLog::writeLog(
            $node->order_id,
            $node->id,
            OrderOperateLog::TYPE_CHANGE_ORDER,
            $content
        );
        $node->has_change_order = true;
        $node->step = 0;
        if ($node->node_id = OrderNode::TYPE_BL_COPY){
            $now = Carbon::now();
            $node->is_top = 1;
            $node->top_at = $now->format('Y-m-d H:i:s');
            $node->top_finish_time = $now->addHours(3)->format('Y-m-d H:i:s');
        }
        if ($node->node_id == OrderNode::TYPE_SUR){
            //SUR
            $node->step = 1;
            //开启FM
            OrderNode::changeNodeEnable($node->order_id, OrderNode::TYPE_FM, 1);
        }
        return $node->save();
    }


    public static function mailTemplate($request)
    {
        $template = MailTemplate::query()->where('type', $request['type'])->first();
        if (!$template){
            throw new ErrorException('邮件模板不存在');
        }
        return MailTemplate::render($template, $request['type'], $request['order_id']);
    }

    //保存集装箱信息
    public static function saveContainers($order, $containers)
    {
        foreach ($containers as $container) {
            $con = Container::query()->updateOrCreate([
                'id' => $container['id'] ?? 0
            ], [
                'order_id' => $order->id,
                'common' => $container['common'] ?? '',
                'container_type' => $container['container_type'] ?? '',
                'quantity' => $container['quantity']??0,
            ]);
            foreach ($container['details'] as $detail) {
                ContainerDetail::query()->updateOrCreate([
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
                    'seal' => $detail['seal'],
                    'tare' => $detail['tare'],
                    'tare_type' => $detail['tare_type'],
                ]);
            }
        }
    }
}
