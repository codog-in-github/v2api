<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;

/**
 * 体育の日（10月的第二个星期一） - 促进体育和健康。
 */
class Sport implements DynamicHoliday
{
    protected Year $year;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    /**
     * 计算体育の日（10月的第二个星期一）的日期
     *
     * @return array<string> 返回日期字符串数组
     */
    public function getDay(): array
    {
        $year = $this->year->getYear();
        $startWeek = Calendar::getWeek("$year-10-01");

        // 计算10月的第二个星期一的日期
        $date = 15 - $startWeek;

        // 格式化日期，使其为两位数
        $formattedDate = str_pad((string)$date, 2, '0', STR_PAD_LEFT);

        return ["10-$formattedDate"];
    }
}
