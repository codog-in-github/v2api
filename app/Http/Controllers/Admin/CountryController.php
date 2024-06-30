<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Logic\BankLogic;
use App\Logic\CountryLogic;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function list(Request $request)
    {
       $res = CountryLogic::list($request);
       return $this->success($res);
    }

    public function tree(Request $request)
    {
        $res = CountryLogic::getTree($request);
        return $this->success($res);
    }
}
