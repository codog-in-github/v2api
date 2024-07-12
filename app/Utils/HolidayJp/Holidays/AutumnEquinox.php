<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;

/**
 * 秋分日
 */
class AutumnEquinox implements DynamicHoliday
{
    protected Year $year;

    private const D = 0.2422;
    private const C = 23.042;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    /**
     * 计算秋分日的日期
     *
     * @return array<string> 返回日期字符串数组
     */
    public function getDay(): array
    {
        $year = $this->year->getYear();
        $Y = $year - 2000;
        $L = (int) ($Y / 4);
        $day = (int) ($Y * self::D + self::C - $L);

        return ["09-$day"];
    }
}
