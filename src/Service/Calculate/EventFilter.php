<?php

namespace App\Service\Calculate;


use App\Model\EventRider;

class EventFilter
{
    private const JUNIOR_CLASSES = ['65см', '50см'];
    public const DEFAULT_FILTER = 'all';
    /**
     * @param array $result
     * @param $selectedFilter
     * @param array $dsqRiders
     * @return array
     */
    public function prepareResult(array $result, $selectedFilter,array $dsqRiders): array
    {

        $filtered = [];
        $filter = [self::DEFAULT_FILTER => 'All'];
        $result = $this->setDSQ($result,$dsqRiders);

        foreach ($result as $riderNumber =>$riderRaw){

            if (isset($item['class'])) {
                $filter[$item['class']] = ucfirst($item['class']);
            }

            if(isset($riderRaw['class'])){
                $filter[$riderRaw['class']] = ucfirst($riderRaw['class']);
            }

            if ($this->shouldExclude($riderRaw, $selectedFilter)) {
                continue;
            }

            $filtered[$riderNumber] = $this->setEventRider($riderRaw, $riderNumber);
        }

        return ['result'=>$filtered,'filter'=>$filter];
    }

    /**
     * @param $riderRaw
     * @param $number
     * @return EventRider
     */
    private function setEventRider($riderRaw,$number): EventRider{

        $eventRider = new EventRider();
        $eventRider->setName($riderRaw['name']);
        $eventRider->setNumber($number);
        $eventRider->setStatus($riderRaw['status']);
        $eventRider->setClass($riderRaw['class']);
        $eventRider->setTotalLaps($riderRaw['total_laps']);
        $eventRider->setBestLapTime($riderRaw['best_lap_time'] ?? '');
        $eventRider->setWorstLapTime($riderRaw['worst_lap_time'] ?? '');
        $eventRider->setTotalTime($riderRaw['total_time']);
        $eventRider->setLapsTimes($riderRaw['laps_times']);

        return $eventRider;
    }

    private function shouldExclude(array $item, string $selectedFilter): bool
    {
        if ($selectedFilter !== 'all' && $item['class'] !== $selectedFilter) {
            return true;
        }

        if ($selectedFilter === 'all' && in_array($item['class'], self::JUNIOR_CLASSES, true)) {
            return true;
        }

        return false;
    }

    private function setDSQ(array $result, array $ridersDSQ): array
    {

        $dsqMap = array_flip($ridersDSQ);

        $normal = [];
        $dsq = [];

        foreach ($result as $riderId => $item) {

            if (isset($dsqMap[(string)$riderId])) {

                $item['total_laps'] = 0;
                $item['best_lap_time'] = null;
                $item['worst_lap_time'] = null;
                $item['total_time'] = '00:00:00:000';
                $item['laps_times'] = [];
                $item['status'] = 'DSQ';

                $dsq[$riderId] = $item;

            } else {
                $normal[$riderId] = $item;
            }
        }

        return $normal + $dsq;
    }
}
