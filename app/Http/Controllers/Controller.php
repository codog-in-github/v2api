<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    //
    protected function failJson($msg = 'fail', $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'code' => $code,
            'message' => $msg
        ], $code);
    }
    protected function successJson($data = [], $msg = 'success', $code = Response::HTTP_OK)
    {
        return response()->json([
            'code' => $code,
            'message' => $msg,
            'data' => $data
        ], $code);
    }
}
