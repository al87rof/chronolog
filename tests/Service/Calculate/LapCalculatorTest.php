<?php

namespace App\Tests\Service\Calculate;

use App\Service\Calculate\LapCalculator;
use App\Service\Calculate\TimeFormatter;
use PHPUnit\Framework\TestCase;

class LapCalculatorTest extends TestCase
{
    private LapCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new LapCalculator(new TimeFormatter());
    }

    public function testRiderFinishedTwoLaps(): void
    {
        $racers = [
            '1' => ['0', '60000', '120000'], // старт + 2 круги по 60 сек
        ];
        $ridersName = [
            '1' => ['name' => 'Іван Петренко', 'class' => 'OPEN', 'correction' => 0]
        ];

        $result = $this->calculator->calculateLaps($racers, $ridersName);

        $this->assertSame(2, $result['1']['total_laps']);
        $this->assertSame('FINISHED', $result['1']['status']);
        $this->assertSame('00:01:00:000', $result['1']['best_lap_time']);
    }

    public function testRiderWithNoLapsGetsDNF(): void
    {
        $racers = []; // гонщик не з'явився
        $ridersName = [
            '5' => ['name' => 'Олег Сидоренко', 'class' => 'OPEN', 'correction' => 0]
        ];

        $result = $this->calculator->calculateLaps($racers, $ridersName);

        $this->assertSame('DNF', $result['5']['status']);
        $this->assertSame(0, $result['5']['total_laps']);
    }

    public function testNegativeLapTimeIsSkipped(): void
    {
        $racers = [
            '1' => ['5000', '3000', '10000'] // друга мітка менша — невалідна
        ];
        $ridersName = [
            '1' => ['name' => 'Тест', 'class' => 'A', 'correction' => 0]
        ];

        $result = $this->calculator->calculateLaps($racers, $ridersName);

        $this->assertSame(1, $result['1']['total_laps']); // тільки 1 валідний круг
    }

    public function testBestAndWorstLap(): void
    {
        $racers = [
            '1' => ['0', '60000', '150000', '210000'] // круги: 60сек, 90сек, 60сек
        ];
        $ridersName = [
            '1' => ['name' => 'Тест', 'class' => 'A', 'correction' => 0]
        ];

        $result = $this->calculator->calculateLaps($racers, $ridersName);

        $this->assertSame('00:01:00:000', $result['1']['best_lap_time']);
        $this->assertSame('00:01:30:000', $result['1']['worst_lap_time']);
    }
}
