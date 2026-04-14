<?php

namespace App\Tests\Service\Calculate;

use App\Service\Calculate\TimeFormatter;
use Exception;
use PHPUnit\Framework\TestCase;

class TimeFormatterTest extends TestCase
{
    private TimeFormatter $formatter;

    protected function setUp(): void
    {
        $this->formatter = new TimeFormatter();
    }

    // ==========================================
    // formatTime()
    // ==========================================

    /**
     * @throws Exception
     */
    public function testFormatTimeZero(): void
    {
        $this->assertSame('00:00:00:000', $this->formatter->formatTime(0));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeOneSecond(): void
    {
        $this->assertSame('00:00:01:000', $this->formatter->formatTime(1000));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeOneMinute(): void
    {
        $this->assertSame('00:01:00:000', $this->formatter->formatTime(60000));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeOneHour(): void
    {
        $this->assertSame('01:00:00:000', $this->formatter->formatTime(3600000));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeWithMilliseconds(): void
    {
        $this->assertSame('00:01:01:500', $this->formatter->formatTime(61500));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeComplex(): void
    {
        // 1 година 2 хвилини 3 секунди 456 мс
        $ms = (1 * 3600 * 1000) + (2 * 60 * 1000) + (3 * 1000) + 456;
        $this->assertSame('01:02:03:456', $this->formatter->formatTime($ms));
    }

    // ==========================================
    // parseTimeToMilliseconds()
    // ==========================================

    public function testParseInvalidFormatThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // передаємо невірний формат — метод має кинути виключення
        $this->formatter->parseTimeToMilliseconds('01:02:03');
    }

    /**
     * @throws Exception
     */
    public function testParseZero(): void
    {
        $this->assertSame(0, $this->formatter->parseTimeToMilliseconds('00:00:00:000'));
    }

    /**
     * @throws Exception
     */
    public function testParseOneSecond(): void
    {
        $this->assertSame(1000, $this->formatter->parseTimeToMilliseconds('00:00:01:000'));
    }

    /**
     * @throws Exception
     */
    public function testParseWithMilliseconds(): void
    {
        $this->assertSame(61500, $this->formatter->parseTimeToMilliseconds('00:01:01:500'));
    }

//    public function testParseInvalidFormatThrowsException(): void
//    {
//        $this->expectException(\InvalidArgumentException::class);
//        $this->formatter->parseTimeToMilliseconds('01:02:03'); // тільки 3 частини замість 4
//    }

    // ==========================================
    // Round-trip: formatTime -> parseTimeToMilliseconds
    // ==========================================

    /**
     * @throws Exception
     */
    public function testRoundTrip(): void
    {
        $original = 3723456;
        $formatted = $this->formatter->formatTime($original);
        $parsed = $this->formatter->parseTimeToMilliseconds($formatted);

        $this->assertSame($original, $parsed);
    }

    // ==========================================
    // formatTimeDiff()
    // ==========================================

    /**
     * @throws Exception
     */
    public function testFormatTimeDiffZeroReturnsEmpty(): void
    {
        $this->assertSame('—', $this->formatter->formatTimeDiff(0));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeDiffNegativeReturnsEmpty(): void
    {
        $this->assertSame('—', $this->formatter->formatTimeDiff(-500));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeDiffSeconds(): void
    {
        $this->assertSame('+5.123', $this->formatter->formatTimeDiff(5123));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeDiffMinutes(): void
    {
        $this->assertSame('+1:05.123', $this->formatter->formatTimeDiff(65123));
    }

    /**
     * @throws Exception
     */
    public function testFormatTimeDiffHours(): void
    {
        $this->assertSame('+1:01:05.123', $this->formatter->formatTimeDiff(3665123));
    }


    /**
     * @throws Exception
     */
    public function testTimeToMs(){
        $time = '00:01:05:123';
        $this->assertSame(65123,$this->formatter->timeToMs($time));
    }

    /**
     * @throws Exception
     */
    public function testMsToTime(){
        $ms = 65123;
        $this->assertSame('00:01:05.123',$this->formatter->msToTime($ms));
    }
}
