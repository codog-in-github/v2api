<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;


// 敬老の日（9月的第三个星期一） - 敬老节，纪念老年人。
class Aged implements DynamicHoliday
{
    protected Year $year;

    public function __construct(Year $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {
        $startWeek = Calendar::getWeek($this->year . '-09-01');
        if($startWeek === 0) {
            return ["09-15"];
        }
        $date = (21 - $startWeek + 1);
        if ($date < 10) {
            $date = "0$date";
        }
        return ["09-$date"];
    }
}
