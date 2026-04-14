<?php

namespace App\Model;


class Lap
{

    private int     $riderId = 0;
    private int     $lap = 0;
    private string  $time ='';
    private string  $delta = '';
    private bool    $isBest = false;
    private bool    $isWorst = false;
    private string  $lapPosition = '';
    private string  $gapToLeader = '';
    private int     $deltaMS = 0;

    /**
     * @param int $riderId
     */
    public function setRiderId(int $riderId): void
    {
        $this->riderId = $riderId;
    }

    /**
     * @return int
     */
    public function getRiderId(): int
    {
        return $this->riderId;
    }

    /**
     * @param string $delta
     */
    public function setDelta(string $delta): void
    {
        $this->delta = $delta;
    }

    /**
     * @return string
     */
    public function getDelta(): string
    {
        return $this->delta;
    }

    /**
     * @param string $gapToLeader
     */
    public function setGapToLeader(string $gapToLeader): void
    {
        $this->gapToLeader = $gapToLeader;
    }

    /**
     * @return string
     */
    public function getGapToLeader(): string
    {
        return $this->gapToLeader;
    }

    /**
     * @param bool $isBest
     */
    public function setIsBest(bool $isBest): void
    {
        $this->isBest = $isBest;
    }

    /**
     * @return bool
     */
    public function isBest(): bool
    {
        return $this->isBest;
    }

    /**
     * @param bool $isWorst
     */
    public function setIsWorst(bool $isWorst): void
    {
        $this->isWorst = $isWorst;
    }

    /**
     * @return bool
     */
    public function isWorst(): bool
    {
        return $this->isWorst;
    }

    /**
     * @param int $lap
     */
    public function setLap(int $lap): void
    {
        $this->lap = $lap;
    }

    /**
     * @return int
     */
    public function getLap(): int
    {
        return $this->lap;
    }

    /**
     * @param string $lapPosition
     */
    public function setLapPosition(string $lapPosition): void
    {
        $this->lapPosition = $lapPosition;
    }

    /**
     * @return string
     */
    public function getLapPosition(): string
    {
        return $this->lapPosition;
    }

    /**
     * @param string $time
     */
    public function setTime(string $time): void
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @param int $deltaMS
     */
    public function setDeltaMS(int $deltaMS): void
    {
        $this->deltaMS = $deltaMS;
    }

    /**
     * @return int
     */
    public function getDeltaMS(): int
    {
        return $this->deltaMS;
    }

}
