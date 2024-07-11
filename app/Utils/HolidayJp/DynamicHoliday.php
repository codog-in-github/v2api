<?php

namespace App\Utils\HolidayJp;

interface DynamicHoliday
{
    public function __construct(string $year);

    public function getDay(): array;
}
