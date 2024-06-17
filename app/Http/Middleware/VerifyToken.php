<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(
            !$request->header('token') ||
            !$this->verify($request->header('token'))
        ) {
            return $this->fail();
        }
        return $next($request);
    }

    protected function fail(): Response
    {
        return response()->json([
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => 'token is required'
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function verify(string $token): bool
    {
        $expireTime = env('token_expire_time', 3600);
        $tokenKey = "token:$token";
        $expireAt = Redis::get($tokenKey)['expire_at'] ?? 0;
        if($expireAt < time()) {
            Redis::del($tokenKey);
            return false;
        }
        Redis::expire($tokenKey, $expireTime);
        return true;
    }
}
