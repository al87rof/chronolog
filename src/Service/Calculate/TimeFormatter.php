<?php

namespace App\Service\Calculate;

use App\Interfaces\Service\TimeFormatterInterface;

class TimeFormatter implements TimeFormatterInterface
{
    public function formatTime($milliseconds): string
    {
        $totalSeconds = $milliseconds / 1000;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = floor($totalSeconds % 60);
        $remainingMilliseconds = $milliseconds % 1000;

        return sprintf("%02d:%02d:%02d:%03d", $hours, $minutes, $seconds, $remainingMilliseconds);
    }

    public function parseTimeToMilliseconds(string $time): int
    {
        $parts = explode(':', $time);

        if (count($parts) !== 4) {
            throw new \InvalidArgumentException($time);
        }

        [$hours, $minutes, $seconds, $milliseconds] = array_map('intval', $parts);
        return ($hours * 3600 * 1000) + ($minutes * 60 * 1000) + ($seconds * 1000) + $milliseconds;
    }

    public function formatTimeDiff(int $milliseconds): string
    {
        if ($milliseconds <= 0) {
            return '—';
        }

        $totalSeconds = floor($milliseconds / 1000);

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        $ms = $milliseconds % 1000;

        if ($hours > 0) {
            // +H:MM:SS.mmm
            return sprintf("+%d:%02d:%02d.%03d", $hours, $minutes, $seconds, $ms);
        }

        if ($minutes > 0) {
            // +M:SS.mmm
            return sprintf("+%d:%02d.%03d", $minutes, $seconds, $ms);
        }

        // +SS.mmm
        return sprintf("+%d.%03d", $seconds, $ms);
    }

    public  function timeToMs(string $time): int {
        list($h, $m, $s, $ms) = explode(':', $time);
        return ((int)$h * 3600 + (int)$m * 60 + (int)$s) * 1000 + (int)$ms;
    }

    public function msToTime(int $ms): string {
        $hours = floor($ms / 3600000);
        $minutes = floor(($ms % 3600000) / 60000);
        $seconds = floor(($ms % 60000) / 1000);
        $milliseconds = $ms % 1000;

        return sprintf('%02d:%02d:%02d.%03d', $hours, $minutes, $seconds, $milliseconds);
    }
}
