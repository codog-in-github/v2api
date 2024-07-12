<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;

/**
 * 根据每年的年份计算春分日期。
 * 使用通式寿星公式计算：
 * [Y×D+C]-L
 * Y=年代数、D=0.2422、L=闰年数、C取决于节气和年份。
 * 本世纪春分的C值=20.646。
 */
class SpringEquinox implements DynamicHoliday
{
    protected Year $year;

    private const D = 0.2422;
    private const C = 20.646;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {
        $year = $this->year->getYear();
        $Y = $year - 2000;
        $L = (int) ($Y / 4);
        $day = (int) (($Y * self::D + self::C) - $L);
        return ["03-$day"];
    }
}
