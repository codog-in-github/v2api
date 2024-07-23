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

    const BKG_TYPE_T = 1;  //通
    const BKG_TYPE_T_BK_Y = 2; //通+BK+運
    const BKG_TYPE_BK = 3; //BK
    const BKG_TYPE_T_BK = 4; //通+BK
    const BKG_TYPE_T_Y = 5; //通+運
    const BKG_TYPE_BK_Y = 6;//BK+運
    const BKG_TYPE_Y = 7;//运
    const BKG_TYPE_C = 8; //自定义

    const BKG_TYPE_TEXT_ARR = [
        self::BKG_TYPE_T => '通',
        self::BKG_TYPE_T_BK_Y => '通+BK+運',
        self::BKG_TYPE_BK => 'BK',
        self::BKG_TYPE_T_BK => '通+BK',
        self::BKG_TYPE_T_Y => '通+運',
        self::BKG_TYPE_BK_Y => 'BK+運',
        self::BKG_TYPE_Y => '運',
        self::BKG_TYPE_C => '自定义',
    ];


}
