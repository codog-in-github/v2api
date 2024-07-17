<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Request\Admin\RequestBookRequest;
use App\Logic\OrderLogic;
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
     * 修改请求书状态 is_confirm is_entry
     * @param RequestBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(RequestBookRequest $request)
    {
        return $this->success(RequestBookLogic::changeStatus($request));
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


    public function pdfByStream(RequestBookRequest $request)
    {
        return $this->success(RequestBookLogic::pdfByStream($request));
    }


}
