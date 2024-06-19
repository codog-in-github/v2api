<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class I18n
{

    protected const LANG_CLASS = [
        'zh-cn' => \App\Utils\I18n\ZhCn::class,
        'jp' => \App\Utils\I18n\Jp::class,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->header('lang', 'zh-cn');
        app()->singleton('lang', function() use ($lang) {
            return new self::LANG_CLASS[$lang];
        });
        return $next($request);
    }
}
