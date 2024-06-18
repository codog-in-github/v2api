<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{

    public const CODE_SUCCESS = 0;
    public const CODE_FAIL = -1;
    public const CODE_UNAUTHORIZED = -2;
    public const CODE_TOKEN_EXPIRED = -3;
    //
    protected function token()
    {
        return app('token');
    }

    protected function lang($key)
    {
        return app('lang')->getLang($key);
    }
    protected function failJson($msg = 'fail', $code = self::CODE_FAIL, $HTTPCode = Response::HTTP_OK) : JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $msg
        ], $HTTPCode);
    }
    protected function successJson($data = [], $msg = 'success', $code = self::CODE_SUCCESS, $HTTPCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $msg,
            'data' => $data
        ], $HTTPCode);
    }
}
