<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;


/**
 * 可以根据每年的立春的日期，推算全年的24节气，每年的
 * 立春之后每个15天就是一个节气，只要计算出每年第一个
 * 节气，其它节气加15倍数 节气日期速算法：
 *
 * 通式寿星公式——[Y×D+C]-L
 *
 * Y=年代数、D=0.2422、L=闰年数、C取决于节气和年份。
 * 本世纪立春的C值=4.475
 *
 * 求2017年的立春日期如下：
 *
 * [2017×0.2422+4.475]-[2017/4-15]=492-489=3
 *
 * 所以2017年的立春日期是2月3日，就是这么简单。
 */
class SpringEquinox implements DynamicHoliday
{
    protected Year $year;
    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {
        $Y = $this->year->getYear() - 2000;
        $D = 0.2422;
        $C = 20.646;
        $L = (int) ($Y / 4);
        $day = (int) (($Y * $D + $C) - $L);
        return ["03-$day"];
    }
}
