<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Request\Admin\CustomerRequest;
use App\Logic\BankLogic;
use App\Logic\CustomerLogic;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function list(Request $request)
    {
       $res = CustomerLogic::list($request);
       return $this->success($res);
    }

    public function save(CustomerRequest $request)
    {
        return $this->success(CustomerLogic::save($request));
    }

    public function delete(CustomerRequest $request)
    {
        return $this->success(CustomerLogic::delete($request));
    }
}
