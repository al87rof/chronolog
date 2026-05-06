<?php

namespace App\Service\Calculate;


use App\Entity\Classes;
use App\Entity\Riders;
use App\Model\EventRider;
use App\Model\Standings\RiderEventPointsDto;
use App\Model\Standings\RiderStandingDto;
use App\Model\Standings\StandingsTableDto;
use App\Repository\ClassesRepository;
use App\Repository\EventsRepository;
use App\Repository\PositionPointsRepository;
use App\Service\RiderProvider\RiderCollector;


class StandingsService
{

    public function __construct(private PositionPointsRepository $pointsRepository,
                                private EventsRepository $eventsRepository,
                                private ClassesRepository $classesRepository,
                                private EventFilter $eventFilter,
                                private RiderCollector $riderCollector

    )
    {
    }

    /**
     * @param $class
     * @param $year
     * @return StandingsTableDto
     */
    public function buildStandings($class,$year): StandingsTableDto
    {
        $events = $this->eventsRepository->getEventsForStandings(new \DateTime("$year-01-01"));

        $grouped = [];

        foreach ($events as $event) {

            $eventData = $this->eventFilter->prepareResult(
                $event->getResultsJson(),
                $class,
                $event->getRidersListDsqArray()
            );

            $position = 1;

            /** @var EventRider $riderDto */
            foreach ($eventData['result'] as $riderDto) {

                if($riderDto->getStatus() == 'FINISHED'){
                    $rider = $this->riderCollector->getRiderByName($riderDto->getName());
                    $riderName = $rider ? $rider->getName() : $riderDto->getName();

                    if (!isset($grouped[$riderName])) {
                        $grouped[$riderName] = [
                            'events' => [],
                            'total' => 0,
                        ];
                    }

                    $points = $this->pointsRepository
                            ->findOneBy(['position' => $position])
                            ?->getPoints() ?? 0;

                    $grouped[$riderName]['events'][] = new RiderEventPointsDto(
                        eventName: $event->getName(),
                        points: $points
                    );

                    $grouped[$riderName]['total'] += (int) $points;

                    $position++;
                }
            }
        }

        uasort($grouped, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        $result = [];

        foreach ($grouped as $riderName => $data) {
            $result[] = new RiderStandingDto(
                riderName: $riderName,
                events: $data['events'],
                totalPoints: $data['total']
            );
        }

        return new StandingsTableDto($result);
    }


    public function getAvailableYears():array{
        return  $this->eventsRepository->getAvailableYears();
    }

    public function getAvailableClasses($type = 1):array{
        return  $this->classesRepository->getAvailableClasses($type);
    }

    public function normalizeClass(string $class): ?string
    {
        $class = mb_strtolower(trim($class));

        return Classes::CLASS_ALIASES[$class] ?? null;
    }

}
