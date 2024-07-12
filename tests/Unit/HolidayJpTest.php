<?php

namespace Tests\Unit;

use App\Utils\HolidayJp\Calendar;
use App\Utils\HolidayJp\Holidays\Sport;
use App\Utils\HolidayJp\Year;
use PHPUnit\Framework\TestCase;

class HolidayJpTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetWeek()
    {
        $this->assertEquals(1, Calendar::getWeek('2024-10-01'));
    }

    public function testDynamicHolidays()
    {
        // Test static holidays
        $this->assertEquals(
            ['10-14'],
            (new Sport(new Year('2024')))->getDay()
        );

    }
    public function testGetHolidays()
    {
        // Test static holidays
        $this->assertEquals(
            [
                '2024-01-01',
                '2024-01-08',
                '2024-02-11',
                '2024-02-23',
                '2024-03-20',
                '2024-04-29',
                '2024-05-03',
                '2024-05-04',
                '2024-05-05',
                '2024-07-15',
                '2024-08-11',
                '2024-09-16',
                '2024-09-22',
                '2024-10-14',
                '2024-11-03',
                '2024-11-23',
            ],
            Calendar::getHolidays('2024-01-01', '2024-12-31')
        );
        $this->assertEquals(
            [
                '2024-01-01',
                '2024-01-08',
                '2024-02-11',
                '2024-02-23',
                '2024-03-20',
                '2024-04-29',
                '2024-05-03',
                '2024-05-04',
                '2024-05-05',
                '2024-07-15',
                '2024-08-11',
                '2024-09-16',
                '2024-09-22',
                '2024-10-14',
                '2024-11-03',
                '2024-11-23',
                '2025-01-01',
                '2025-01-13',
                '2025-02-11',
                '2025-02-23',
                '2025-03-20',
                '2025-04-29',
                '2025-05-03',
                '2025-05-04',
                '2025-05-05',
                '2025-07-21',
                '2025-08-11',
                '2025-09-15',
                '2025-09-23',
                '2025-10-13',
                '2025-11-03',
                '2025-11-23',
            ],
            Calendar::getHolidays('2024-01-01', '2025-12-31')
        );

    }
}
