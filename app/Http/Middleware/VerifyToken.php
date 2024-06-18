<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(
            !$request->header('token') ||
            !$this->verify($request->header('token'), $request)
        ) {
            return $this->fail();
        }
        return $next($request);
    }

    protected function fail(): Response
    {
        return response()->json([
            'code' => Controller::CODE_UNAUTHORIZED,
            'message' => 'UNAUTHORIZED'
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function verify(string $token): bool
    {
        $expireTime = env('TOKEN_EXPIRE_TIME', 3600);
        $tokenKey = "token:$token";
        $expireAt = Redis::get($tokenKey)['expire_at'] ?? 0;
        if($expireAt < time()) {
            Redis::del($tokenKey);
            return false;
        }
        $this->exportToken($token);
        Redis::expire($tokenKey, $expireTime);
        return true;
    }

    protected function exportToken($token): void
    {
        app()->singleton('token', function() use ($token) {
            return $token;
        });
    }
}
