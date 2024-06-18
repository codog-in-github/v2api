<?php

namespace App\Utils\I18n;

class Jp implements I18n
{
    private const strings = [
        'req.err' => '请求错误',
        'req.err.param' => '参数错误',
        'req.err.param.missing' => '缺少参数',
        'req.err.param.invalid' => '参数无效',
        'req.err.param.invalid.type' => '参数类型无效',
        'req.err.param.invalid.format' => '参数格式无效',
        'req.err.param.invalid.length' => '参数长度无效',
        'req.err.param.invalid.range' => '参数范围无效',
        'req.err.param.invalid.value' => '参数值无效',
        'login.pwd.err' => '密码错误',
        'login.usr.err' => '用户名错误',
        'login.success' => '登录成功',
        'login.fail' => '登录失败',
        'login.token.expired' => 'token过期',
        'login.token.err' => 'token错误',
        'login.token.success' => 'token验证成功',
        'login.token.fail' => 'token验证失败',
        'login.token.unauthorized' => '未登录',
        'login.token.unauthorized.err' =>'未登录',
    ];
    public function getLang(string $key): string
    {
        return self::strings[$key] ?? '';
    }
}
