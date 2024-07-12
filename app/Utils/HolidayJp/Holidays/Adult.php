<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;

/**
 * 成人の日（1月的第二个星期一） - 庆祝达到成年（20岁）的年轻人。
 */
class Adult implements DynamicHoliday
{
    protected Year $year;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    /**
     * 计算成人の日（1月的第二个星期一）的日期
     *
     * @return array<string> 返回日期字符串数组
     */
    public function getDay(): array
    {
        $year = $this->year->getYear();
        $startWeek = Calendar::getWeek("$year-01-01");

        // 计算1月的第二个星期一的日期
        $date = 8 + (7 - $startWeek) % 7;

        // 格式化日期，使其为两位数
        $formattedDate = str_pad((string)$date, 2, '0', STR_PAD_LEFT);

        return ["01-$formattedDate"];
    }
}
