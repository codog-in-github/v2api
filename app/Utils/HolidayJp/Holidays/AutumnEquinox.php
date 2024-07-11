<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;

/**
 * 秋分日
 */
class AutumnEquinox implements DynamicHoliday
{
    protected string $year;
    public function __construct(string $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {

        $springEquinox = new \DateTime("{$this->year}-09-22", new \DateTimeZone('UTC'));
        if ($springEquinox->format('Y') !== $this->year) {
            return ["09-23"];
        }
        return ["09-22"];
    }
}
