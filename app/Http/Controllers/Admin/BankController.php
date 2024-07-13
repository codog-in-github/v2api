<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Logic\BankLogic;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function list(Request $request)
    {
       $res = BankLogic::list($request);
       return $this->success($res);
    }

    public function selectList(Request $request)
    {
        return $this->success(BankLogic::selectList($request));
    }

    public function optionList(Request $request)
    {
        return $this->success(BankLogic::optionList($request));
    }
    public function departmentList(Request $request)
    {
        return $this->success(BankLogic::departmentList($request));
    }
}
