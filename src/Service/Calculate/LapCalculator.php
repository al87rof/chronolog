<?php

namespace App\Service\Calculate;

use App\Interfaces\Service\TimeFormatterInterface;
use App\Model\EventRider;
use App\Model\Lap;

class LapCalculator
{
    private TimeFormatterInterface $timeFormatter;

    public function __construct(TimeFormatterInterface  $timeFormatter)
    {
        $this->timeFormatter = $timeFormatter;
    }


    public function calculateLaps(array $racers, array $ridersName): array
    {
        $lapsData = [];

        foreach ($ridersName as $racerId => $rider) {

            $lapTimes = $racers[$racerId] ?? [];

            $totalLaps = 0;
            $bestLapTime = null;
            $worstLapTime = null;
            $lapsTimes = [];
            $totalTime = 0;

            if (count($lapTimes) >= 2) {
                for ($i = 1, $c = count($lapTimes); $i < $c; $i++) {

                    $lapTime = (int)$lapTimes[$i] - (int)$lapTimes[$i - 1];

                    if ($lapTime <= 0) {
                        continue;
                    }

                    $totalLaps++;
                    $lapsTimes[] =$this->timeFormatter->formatTime($lapTime);
                    $totalTime += $lapTime;

                    $bestLapTime = $bestLapTime === null ? $lapTime : min($bestLapTime, $lapTime);
                    $worstLapTime = $worstLapTime === null ? $lapTime : max($worstLapTime, $lapTime);
                }
            }

            $lapsData[$racerId] = [
                'total_laps'     => $totalLaps,
                'best_lap_time'  => $bestLapTime ? $this->timeFormatter->formatTime($bestLapTime) : null,
                'worst_lap_time' => $worstLapTime ?$this->timeFormatter->formatTime($worstLapTime) : null,
                'total_time'     => $this->timeFormatter->formatTime($totalTime),
                'laps_times'     => $lapsTimes,
                'name'           => $rider['name'],
                'class'          => $rider['class'],
                'status'         => $totalLaps > 0 ? 'FINISHED' : 'DNF',
            ];
        }

        return $lapsData;
    }

    /**
     * @param array $result
     * @return mixed
     */
    public function lapChartCalculator(array $result){
        $result = $this->prepareLapCharResult($result);
        return$this->calculatePositionOnLap($result);
    }


    private function prepareLapCharResult($result){
        $position = 1;
        /** @var EventRider $rider */
        foreach ($result as  $rider) {
            $rider->setPosition($position++);

            if($rider->getTotalLaps() == 0){
                continue;
            }

            $lapTimesMs = array_map([$this->timeFormatter, 'timeToMs'], $rider->getLapsTimes());

            $bestLapMs  = min($lapTimesMs);
            $worstLapMs = max($lapTimesMs);

            foreach ($rider->getLapsTimes() as $lapNumber => $time) {

                $currentMs = $lapTimesMs[$lapNumber];
                $deltaMs   = $currentMs - $bestLapMs;
                $lap = new Lap();

                $lap->setLap($lapNumber);
                $lap->setDelta($this->timeFormatter->msToTime($deltaMs));
                $lap->setTime($time);
                $lap->setIsBest( $currentMs === $bestLapMs);
                $lap->setIsWorst( $currentMs === $worstLapMs);
                $lap->setLapPosition('');
                $lap->setGapToLeader('');

                $rider->addLap($lap);
            }
        }

        return $result;
    }

    private function calculatePositionOnLap($result){
        $maxLaps = 0;
        /** @var EventRider $rider */
        foreach ($result as $rider) {
            if ($rider->getTotalLaps() > 0) {
                $maxLaps = max($maxLaps, $rider->getTotalLaps());
            }
        }

        $cumulativeTimes = [];

        for ($lapIndex = 0; $lapIndex < $maxLaps; $lapIndex++) {

            $lapTotals = [];
            /** @var EventRider $rider */
            foreach ($result as  $rider) {
                $riderNumber = $rider->getNumber();

                if ($rider->getTotalLaps() == 0 || !$rider->issetLap($lapIndex)) {
                    continue;
                }

                if (!isset($cumulativeTimes[$riderNumber])) {
                    $cumulativeTimes[$riderNumber] = 0;
                }

                $cumulativeTimes[$riderNumber] +=  $this->timeFormatter->timeToMs($rider->getLapsTimes()[$lapIndex]);
                $lapTotals[$riderNumber] = $cumulativeTimes[$riderNumber];
            }


            asort($lapTotals, SORT_NUMERIC);

            if (empty($lapTotals)) {
                continue;
            }

            $leaderTime = reset($lapTotals);
            $position = 1;


            foreach ($lapTotals as $riderNumber => $totalTime) {
                $gap = $totalTime - $leaderTime;
                /** @var EventRider $rider */
                $rider = $result[$riderNumber];

                $lap = $rider->getLapByNumber($lapIndex);

                $lap->setLapPosition($position);
                $lap->setGapToLeader(
                    $position === 1
                        ? 'LEADER'
                        : '+' . $this->timeFormatter->msToTime($gap)
                );

                $position++;
            }
        }

       return $result;
    }


    public function prepareLiveResult(array $result): array
    {
        $leaderTime = 0;
        $leaderLaps = 0;
        $position = 1;

        /** @var EventRider $rider */
        foreach ($result as $rider) {
            $riderTime = $this->timeFormatter->parseTimeToMilliseconds($rider->getTotalTime());
            $riderLaps = $rider->getTotalLaps();

            if ($position == 1) {
                $leaderTime = $riderTime;
                $leaderLaps = $riderLaps;
                $rider->setDiffMs(0);
                $rider->setDiff('');
            } else {
                $lapDiff = $leaderLaps - $riderLaps;
                if ($lapDiff > 0) {
                    $rider->setDiffMs(500000);
                    $rider->setDiff("+ $lapDiff LAP");
                } else {
                    $diffMs = $riderTime - $leaderTime;
                    $rider->setDiffMs($diffMs);
                    $rider->setDiff( $this->timeFormatter->formatTimeDiff($diffMs));
                }
            }
            $position++;
        }

        return $result;
    }


}
