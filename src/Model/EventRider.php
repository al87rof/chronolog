<?php

namespace App\Model;


class EventRider
{
    private string  $name = '';
    private int $totalLaps = 0;
    private string $bestLapTime ='';
    private string $worstLapTime = '';
    private string  $totalTime = '';
    private array  $lapsTimes = [];
    private array  $laps = [];
    private string $class = '';
    private array $classes = [];
    private array $allClasses = [];
    private string $number = '';
    private int $position = 0;
    private string $status = '';
    private int  $diffMs = 0;
    private string $diff = '';

    /**
     * @return string
     */
    public function getRiderClass(): string
    {
        return $this->class;
    }

    /**
     * @return array
     */
    public function getRiderClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return int
     */
    public function getRiderNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getRiderStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getRiderPosition(): int
    {
        return $this->position;
    }

    /**
     * @return array
     */
    public function getAllClasses(): array
    {
        return $this->allClasses;
    }

    /**
     * @return string
     */
    public function getBestLapTime(): string
    {
        return $this->bestLapTime;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getTotalLaps(): int
    {
        return $this->totalLaps;
    }

    /**
     * @return string
     */
    public function getTotalTime(): string
    {
        return $this->totalTime;
    }

    /**
     * @return string
     */
    public function getWorstLapTime(): string
    {
        return $this->worstLapTime;
    }

    /**
     * @return array
     */
    public function getLapsTimes(): array
    {
        return $this->lapsTimes;
    }

    /**
     * @param array $allClasses
     */
    public function setAllClasses(array $allClasses): void
    {
        $this->allClasses = $allClasses;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * @param array $classes
     */
    public function setClasses(array $classes): void
    {
        $this->classes = $classes;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param string $bestLapTime
     */
    public function setBestLapTime(string $bestLapTime): void
    {
        $this->bestLapTime = $bestLapTime;
    }

    /**
     * @param array $lapsTimes
     */
    public function setLapsTimes(array $lapsTimes): void
    {
        $this->lapsTimes = $lapsTimes;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $totalLaps
     */
    public function setTotalLaps(int $totalLaps): void
    {
        $this->totalLaps = $totalLaps;
    }

    /**
     * @param string $totalTime
     */
    public function setTotalTime(string $totalTime): void
    {
        $this->totalTime = $totalTime;
    }

    /**
     * @param string $worstLapTime
     */
    public function setWorstLapTime(string $worstLapTime): void
    {
        $this->worstLapTime = $worstLapTime;
    }

    /**
     * @param array $laps
     */
    public function setLaps(array $laps): void
    {
        $this->laps = $laps;
    }

    /**
     * @return array
     */
    public function getLaps(): array
    {
        return $this->laps;
    }

    /**
     * @param string $diff
     */
    public function setDiff(string $diff): void
    {
        $this->diff = $diff;
    }

    /**
     * @return string
     */
    public function getDiff(): string
    {
        return $this->diff;
    }

    /**
     * @param int $diffMs
     */
    public function setDiffMs(int $diffMs): void
    {
        $this->diffMs = $diffMs;
    }

    /**
     * @return int
     */
    public function getDiffMs(): int
    {
        return $this->diffMs;
    }

    /**
     * @param Lap $lap
     * @return void
     */
    public function addLap(Lap $lap)
    {
        $this->laps[] = $lap;
    }

    /**
     * @param $lapNumber
     * @return Lap
     */
    public function getLapByNumber($lapNumber): Lap
    {
        return $this->laps[$lapNumber];
    }

    /**
     * @param $lapNumber
     * @return bool
     */
    public function issetLap($lapNumber): bool
    {
        return isset($this->lapsTimes[$lapNumber]);
    }


}
