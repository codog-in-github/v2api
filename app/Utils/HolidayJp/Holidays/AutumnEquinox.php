<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;

/**
 * 秋分日
 */
class AutumnEquinox implements DynamicHoliday
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
        $C = 23.042;
        $L = (int) ($Y / 4);
        $day = (int) ($Y * $D + $C - $L);
        return ["09-$day"];
    }
}
