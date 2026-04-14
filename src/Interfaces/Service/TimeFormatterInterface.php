<?php

namespace App\Interfaces\Service;


interface TimeFormatterInterface
{
    public function formatTime($milliseconds): string;

    public function parseTimeToMilliseconds(string $time): int;

    public function formatTimeDiff(int $milliseconds): string;

    public  function timeToMs(string $time): int;

    public function msToTime(int $ms): string;
}
