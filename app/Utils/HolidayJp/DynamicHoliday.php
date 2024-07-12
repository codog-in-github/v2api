<?php

namespace App\Utils\HolidayJp;

interface DynamicHoliday
{
    public function __construct(Year $year);

    public function getDay(): array;
}
