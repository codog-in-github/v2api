<?php

namespace App\Utils\HolidayJp;

class Year
{
    protected int $year;
    public function __construct($year)
    {
        $this->year = (int) $year;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function isLeapYear(): bool
    {
        return $this->year % 4 === 0 &&
            ($this->year % 100 !== 0 || $this->year % 400 === 0);
    }

    public function toString(): string
    {
        return (string) $this->year;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
