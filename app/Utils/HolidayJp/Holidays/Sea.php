<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;


// 海の日（7月的第三个星期一） - 纪念海洋的恩惠
class Sea implements DynamicHoliday
{
    protected Year $year;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {
        $startWeek = Calendar::getWeek($this->year . '-07-01');
        if($startWeek === 0) {
            return ["07-15"];
        }
        $date = (21 - $startWeek + 1);
        if ($date < 10) {
            $date = "0$date";
        }
        return ["07-$date"];
    }
}
