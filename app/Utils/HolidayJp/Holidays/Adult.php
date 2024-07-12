<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;


// 成人の日（1月的第二个星期一） - 庆祝达到成年（20岁）的年轻人。
class Adult implements DynamicHoliday
{
    protected Year $year;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {
        $startWeek = Calendar::getWeek($this->year . '-01-01');
        if($startWeek === 0) {
            return ["01-08"];
        }
        $date = (14 - $startWeek + 1);
        if ($date < 10) {
            $date = "0$date";
        }
        return ["01-$date"];
    }
}
