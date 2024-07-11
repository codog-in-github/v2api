<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;


/**
 * 春分日
 */
class SpringEquinox implements DynamicHoliday
{
    protected string $year;
    public function __construct(string $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {

        $springEquinox = new \DateTime("{$this->year}-03-20", new \DateTimeZone('UTC'));
        // 检查具体的春分日期
        if ($springEquinox->format('Y') !== $this->year) {
            return ["03-21"];
        }
        return ["03-20"];
    }
}
