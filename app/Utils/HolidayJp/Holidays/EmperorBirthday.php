<?php

namespace App\Utils\HolidayJp\Holidays;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\DynamicHoliday;


// 天皇誕生日（2月23日） - 现任天皇的生日。
class EmperorBirthday implements DynamicHoliday
{

    public function __construct(string $year)
    {
    }

    public function getDay(): array
    {
        return ["02-23"];
    }
}
