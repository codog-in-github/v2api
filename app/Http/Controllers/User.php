<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class User extends Controller
{
    //
    protected const SALT = 'whosyourdaddy';
    public function login(Request $request): JsonResponse
    {
        if(!$request->has('usr') || !$request->has('pwd')) {
            return $this->failJson(
                $this->lang('req.err.param')
            );
        }
        $usr = $request->get('usr');
        $pwd = $request->get('pwd');

        if($user = \App\Models\User::userLogin($usr, $pwd)) {
            return $this->failJson(
                $this->lang('login.usr&pwd.err')
            );
        }

        $expireTime = env('TOKEN_EXPIRE_TIME', 3600);
        $expireAt = time() + $expireTime;
        $token = md5(uniqid(self::SALT));

        Redis::set("token:$token", [
            'key' => $token,
            'expire_at' => $expireAt,
            'user' => $user->toArray()
        ]);
        Redis::expire("token:$token", $expireTime);

        return $this->successJson([
            'token' => $token,
            'expire_at' => $expireAt
        ]);
    }
}
