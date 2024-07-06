<?php
namespace Tests\Unit;

use App\Utils\Order\OrderNum;
use PHPUnit\Framework\TestCase;

class OrderNumTest extends TestCase
{
    public function testIsAvailableWithAvailableNumber(): void
    {
        $testCases = [
            1000,
            1002,
            56762,
            8762,
        ];
         foreach ($testCases as $testCase) {
            $this->assertTrue(
                (new OrderNum($testCase))->isAvailable(),
                "Order number $testCase should be available"
            );
        }
    }

    public function testIsAvailableWithUnavailableNumber(): void
    {
        $testCases = [
            10001,
            10402,
            56725632,
            87001962,
        ];
        foreach ($testCases as $testCase) {
            $this->assertFalse(
                (new OrderNum($testCase))->isAvailable(),
                "Order number $testCase should be unavailable"
            );
        }
    }

    public function testNextWithAvailableNumber(): void
    {
        $testCases = [
            10001 => 10002,
            10402 => 10500,
            56725632 => 56725650,
            87001962 => 87002000,
            30231414 => 50000000,
            83271728 => 85000000,
            21900118 => 22000000,
            21700118 => 21700200,
        ];
        foreach ($testCases as $from => $to) {
            $this->assertEquals(
                (new OrderNum($from))->next()->toInt(),
                $to,
                "Next number for {$from} should be {$to}"
            );
        }
    }

}
