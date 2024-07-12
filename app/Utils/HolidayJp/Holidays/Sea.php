<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;

/**
 * 海の日（7月的第三个星期一） - 纪念海洋的恩惠
 */
class Sea implements DynamicHoliday
{
    protected Year $year;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    /**
     * 计算海の日（7月的第三个星期一）的日期
     *
     * @return array<string> 返回日期字符串数组
     */
    public function getDay(): array
    {
        $year = $this->year->getYear();
        $startWeek = Calendar::getWeek("$year-07-01");

        // 计算7月的第三个星期一的日期
        $date = 15 + (7 - $startWeek) % 7;

        // 格式化日期，使其为两位数
        $formattedDate = str_pad((string)$date, 2, '0', STR_PAD_LEFT);

        return ["07-$formattedDate"];
    }
}
