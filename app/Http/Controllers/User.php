<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class User extends Controller
{
    //
    protected const SALT = 'whosyourdaddy';
    public function login(Request $request)
    {
        if(!Request::has('usr') || !Request::has('pwd')) {
            return $this->failJson('参数错误');
        }

        $usr = Request::get('usr');
        $pwd = Request::get('pwd');
        if($usr == 'admin' && $pwd == '123456') {
            $token = md5(self::SALT .uniqid());
            $expireAt = time() + 3600;
            Redis::set("token:$token", [
                'expire_at' => $expireAt
            ]);
            return $this->successJson([
                'token' => $token,
                'expire_at' => $expireAt
            ]);
        } else {
            return$this->failJson('用户名或密码错误');
        }
    }
}
