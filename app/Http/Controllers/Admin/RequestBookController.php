<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Logic\RequestBookLogic;
use Illuminate\Http\Request;

class RequestBookController extends Controller
{
    /**
     * 请求书详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        return $this->success(RequestBookLogic::detail($request->get('id')));
    }

    public function save(Request $request)
    {
        return $this->success(RequestBookLogic::save($request));
    }


}
