<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;


// 体育の日（10月的第二个星期一） - 促进体育和健康。
class Sport implements DynamicHoliday
{
    protected string $year;

    public function __construct(string $year)
    {
        $this->year = $year;
    }

    public function getDay(): array
    {
        $startWeek = Calendar::getWeek($this->year . '10-01');
        if($startWeek === 0) {
            return ["10-08"];
        }
        $date = (14 - $startWeek + 1);
        if ($date < 10) {
            $date = "0$date";
        }
        return ["09-$date"];
    }
}
