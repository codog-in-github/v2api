<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Request\Admin\RequestBookRequest;
use App\Logic\RequestBookLogic;
use App\Utils\PdfUtils;
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

    /**
     * 保存请求书内容
     * @param RequestBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(RequestBookRequest $request)
    {
        return $this->success(RequestBookLogic::save($request));
    }

    /**
     * 删除请求书
     * @param RequestBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(RequestBookRequest $request)
    {
        return $this->success(RequestBookLogic::delete($request));
    }

    /**
     * 导出请求书
     * @param RequestBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportRequestBook(RequestBookRequest $request)
    {
        return $this->success(RequestBookLogic::save($request));
    }


}
