<?php

namespace App\Utils\HolidayJp;

class Calendar
{
    /**
     * @var array<string, self>
     */
    private static array $calendarCaches = [];

    /**
     * @var string[]
     */
    public static array $staticHolidays = [
        '01-01', '02-11', '04-29', '05-03', '05-04', '05-05', '08-11', '11-03', '11-23'
    ];

    /**
     * @var string[]
     */
    public static array $dynamicHolidayClasses = [
        Holidays\Adult::class,
        Holidays\SpringEquinox::class,
        Holidays\AutumnEquinox::class,
        Holidays\EmperorBirthday::class,
        Holidays\Aged::class,
        Holidays\Sport::class,
        Holidays\Sea::class,
    ];

    public array $holidays = [];

    public function __construct($year)
    {
        $yearObj = new Year($year);
        $dynamicHolidays = [];
        foreach (self::$dynamicHolidayClasses as $holidayClass) {
            $dynamicHolidays = array_merge($dynamicHolidays, (new $holidayClass($yearObj))->getDay());
        }
        $allHolidays = array_merge(self::$staticHolidays, $dynamicHolidays);
        $formattedHolidays = array_map(function ($date) use ($year) {
            return "$year-$date";
        }, $allHolidays);
        sort($formattedHolidays, SORT_STRING);
        $this->holidays = $formattedHolidays;
    }

    public static function getHolidays(string $startDate, string $endDate): array
    {
        $startYear = substr($startDate, 0, 4);
        $endYear = substr($endDate, 0, 4);

        if ($startYear != $endYear) {
            return array_merge(
                self::getHolidays($startDate, date("Y-m-d", strtotime("$endYear-01-01") - 24 * 3600)),
                self::getHolidays("$endYear-01-01", $endDate)
            );
        }

        if (!isset(self::$calendarCaches[$startYear])) {
            self::$calendarCaches[$startYear] = new self((int)$startYear);
        }

        $dates = [];
        $currentCalendar = self::$calendarCaches[$startYear];
        $i = 0;

        while ($i < count($currentCalendar->holidays) && $currentCalendar->holidays[$i] < $startDate) {
            $i++;
        }

        for (; $i < count($currentCalendar->holidays) && $currentCalendar->holidays[$i] <= $endDate; $i++) {
            $dates[] = $currentCalendar->holidays[$i];
        }

        return $dates;
    }

    public static function getWeek(string $date): int
    {
        return (strtotime($date) / 3600 / 24 - 4) % 7;
    }
}
