<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 设置一周的开始于星期一
//        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        // 设置一周的结束于星期日
//        Carbon::setWeekEndsAt(Carbon::SUNDAY);
    }
}
