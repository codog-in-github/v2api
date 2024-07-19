<?php

namespace App\Enum;


class OrderEnum
{
    //code返回码
    const STATUS_WAIT = 0;
    const STATUS_ING = 1;
    const STATUS_COM = 2;
    const STATUS_FINISH = 3;

    //订单大状态0未开始 1进行中 2已完成 3订单终止
    const ALL_CODE = [
        self::STATUS_WAIT   => "未开始",
        self::STATUS_ING    => "进行中",
        self::STATUS_COM    => "已完成",
        self::STATUS_FINISH => '订单终止'
    ];


}
