<?php

namespace App\Http\Controllers;

use App\Enum\CodeEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data, $msg = 'success')
    {
        if (is_string($data)) {
            return response()->json([
                'code' => CodeEnum::SUCCESS_CODE,
                'msg' => "",
                'data' => $data,
            ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'code' => CodeEnum::SUCCESS_CODE,
                'msg' => $msg,
                'data' => $data
            ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    public function error($msg, $data = [],$code = CodeEnum::ERROR_CODE)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => null,
        ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function pageReturn($data, $total)
    {
        return response()->json([
            'code' => CodeEnum::SUCCESS_CODE,
            'msg' => 'success',
            'data' => $data,
            'total' => $total
        ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
