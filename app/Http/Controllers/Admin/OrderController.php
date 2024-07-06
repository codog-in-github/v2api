<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Request\Admin\OrderRequest;
use App\Logic\BankLogic;
use App\Logic\ContainerDetailLogic;
use App\Logic\OrderLogic;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 订单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderList(Request $request)
    {
        $res = OrderLogic::orderList($request);
        return $this->pageReturn($res->items(), $res->total());
    }

    /**
     * 集装箱列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function containerList(Request $request)
    {
        $res = ContainerDetailLogic::list($request);
        return $this->success($res->items(), $res->total());
    }

    /**
     * 订单列表 按日历分组
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByCalendar(Request $request)
    {
        $res = OrderLogic::getListByCalendar($request);
        return $this->success($res);
    }

    /**
     * 订单列表 按船社分组
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByShip(Request $request)
    {
        $res = OrderLogic::getListByShip($request);
        return $this->success($res);
    }

    /**
     * 新建订单
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(OrderRequest $request)
    {
        return $this->success(OrderLogic::createOrder($request));
    }

    public function detail(OrderRequest $request)
    {
        return $this->success(OrderLogic::detail($request->get('id')));
    }

    public function editOrder(OrderRequest $request)
    {
        return $this->success(OrderLogic::editOrder($request));
    }

    public function delete(OrderRequest $request)
    {
        return $this->success(OrderLogic::delete($request));
    }

    /**
     * 保存文件
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFile(OrderRequest $request)
    {
        return $this->success(OrderLogic::saveFile($request));
    }

    /**
     * 删除文件
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delFile(OrderRequest $request)
    {
        return $this->success(OrderLogic::delFile($request));
    }

    /**
     * 订单留言
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ErrorException
     */
    public function sendMessage(OrderRequest $request)
    {
        return $this->success(OrderLogic::sendMessage($request));
    }

    /**
     * 订单留言列表
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function messageList(OrderRequest $request)
    {
        return $this->success(OrderLogic::messageList($request));
    }

    /**
     * 留言已读
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function readMessage(OrderRequest $request)
    {
        return $this->success(OrderLogic::readMessage($request));
    }

    /**
     * 报关公司列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomCom(Request $request)
    {
        return $this->success(OrderLogic::getCustomCom($request));
    }


}
