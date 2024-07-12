<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;
use App\Utils\HolidayJp\Year;


// 天皇誕生日（2月23日） - 现任天皇的生日。
class EmperorBirthday implements DynamicHoliday
{

    public function __construct(Year $year)
    {
    }

    public function getDay(): array
    {
        return ["02-23"];
    }
}
