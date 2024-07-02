<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Request\Admin\OrderRequest;
use App\Logic\BankLogic;
use App\Logic\OrderLogic;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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


}
