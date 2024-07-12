<?php

namespace App\Enum;


class CodeEnum
{
    //code返回码
    const SUCCESS_CODE = 200;
    const ERROR_CODE = -1;
    const LOGIN_UNVALID = -2;
    const LOGIN_TOKEN_UNVALID = -3;

    const ALL_CODE = [
        self::SUCCESS_CODE => "成功",
        self::ERROR_CODE => "请求失败",
        self::LOGIN_UNVALID => "登录验证失败",
        self::LOGIN_TOKEN_UNVALID => 'token失效'
    ];

    const COLOR_RED = 3;
    const COLOR_YELLOW = 2;
    const COLOR_GREEN = 1;
}
