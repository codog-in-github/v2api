<?php

namespace App\Utils\HolidayJp;
use Cassandra\Map;

/**
 * 日本的法定假日和每年放假天数如下：
 *
 * 法定假日
 * 元日（1月1日） - 新年第一天。
 * 成人の日（1月的第二个星期一） - 庆祝达到成年（20岁）的年轻人。
 * 建国記念の日（2月11日） - 纪念日本建国。
 * 天皇誕生日（2月23日） - 现任天皇的生日。
 * 春分の日（3月20日或21日） - 纪念春分。
 * 昭和の日（4月29日） - 纪念昭和天皇。
 * 憲法記念日（5月3日） - 纪念日本宪法的实施。
 * みどりの日（5月4日） - 纪念自然。
 * こどもの日（5月5日） - 祝福孩子的健康和幸福。
 * 海の日（7月的第三个星期一） - 纪念海洋的恩惠。
 * 山の日（8月11日） - 纪念山的恩惠。
 * 敬老の日（9月的第三个星期一） - 敬老节，纪念老年人。
 * 秋分の日（9月22日或23日） - 纪念秋分。
 * 体育の日（10月的第二个星期一） - 促进体育和健康。
 * 文化の日（11月3日） - 纪念文化和自由。
 * 勤労感謝の日（11月23日） - 感谢劳动者。
 * 天皇即位の日（不定期，现任天皇即位时）
 *
 * 放假天数
 * 一般来说，日本每年有16个法定假日。此外，根据《国民の祝日に関する法律》，当法定假日
 * 与周日重叠时，下一个工作日将成为补假日（代替假日）。另外，如果两个假日之间只有一天非
 * 假日，那么这一天也将成为假日（夹心假日）。
 *
 */
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
    public static array $dynamicHolidays = [
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
        $dHoliday = [];
        foreach (self::$dynamicHolidays as $dynamicHoliday) {
            $dHoliday = array_merge($dHoliday, (new $dynamicHoliday($year))->getDay());
        }
        $dHoliday = array_merge(self::$staticHolidays, $dHoliday);
        $mapCallee = function ($date) use ($year) {
            return "$year-$date";
        };
        $dHoliday = array_map($mapCallee, $dHoliday);
        sort($dHoliday, SORT_STRING);
        $this->holidays = $dHoliday;
    }
    public static function getHolidays(string $startDate, string $endDate): array
    {

        $startYear = substr($startDate, 0, 4);
        $endYear = substr($endDate, 0, 4);
        if($startYear != $endYear) {
            return array_merge(
                self::getHolidays($startDate, date("Y-m-d", strtotime($endYear-01-01)) - 24 * 3600),
                self::getHolidays("$endYear-01-01", $endDate)
            );
        }
        if(!isset(self::$calendarCaches[$startYear])) {
            self::$calendarCaches[$startYear] = new self($startYear);
        }
        $dates = [];
        $currentCalendar = self::$calendarCaches[$startYear];
        $i = 0;
        while (
            $i < count($currentCalendar->holidays)
            && $currentCalendar->holidays[$i] <= $startDate
        ) {
            $i++;
        }
        for(;$i < count($currentCalendar->holidays) && $currentCalendar->holidays[$i] <= $endDate; $i ++) {
            $dates[] = $currentCalendar->holidays[$i];
        }
        return $dates;
    }

    public static function getWeek($date): int
    {
        return (strtotime($date) / 3600 / 24 - 4) % 7;
    }
}
