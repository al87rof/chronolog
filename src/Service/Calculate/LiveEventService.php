<?php

namespace App\Service\Calculate;

use App\Entity\Events;
use App\Entity\LiveEvents;
use App\Interfaces\Service\TimeFormatterInterface;
use App\Model\EventRider;
use Doctrine\ORM\EntityManagerInterface;


class LiveEventService
{
    private EntityManagerInterface $entityManager;
    private TimeFormatterInterface $formatter;
    private LapCalculator $lapCalculator;

    public function __construct(EntityManagerInterface $entityManager,TimeFormatterInterface $timeFormatter,LapCalculator $lapCalculator)
    {
        $this->entityManager = $entityManager;
        $this->formatter = $timeFormatter;
        $this->lapCalculator = $lapCalculator;
    }

    public function createLiveEvent($riderList, $title): string
    {
        $liveId = $this->genLiveId();

        $liveEvent = new LiveEvents();
        $liveEvent->setEventId($liveId);
        $liveEvent->setData(new \DateTime('now'));
        $liveEvent->setAppLog('');
        $liveEvent->setRidersList($riderList);
        $liveEvent->setTitle($title);
        $liveEvent->setEventImg('');
        $liveEvent->setResult('');
        $liveEvent->setDescription('');

        $this->entityManager->persist($liveEvent);
        $this->entityManager->flush($liveEvent);

        return $liveId;
    }

    public function calculateLive($liveId, $appLog): string
    {
        /** @var LiveEvents $liveEvent */
        $liveEvent = $this->getLiveEventById($liveId);

        $ridersName = [];
        $racers = [];
        $startTime = 0;

        $riderList = $liveEvent->getRidersList();
        $lines = explode("\n", $riderList);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $data = explode(';', $line);
            $id = trim($data[0] ?? '');
            $class = trim($data[1] ?? '');
            $name = trim($data[2] ?? '');
            $correction = (int)($data[3] ?? 0);

            if ($id === '') continue;

            $ridersName[$id] = ['name' => $name, 'class' => $class, 'correction' => $correction];
        }

        $log = preg_split('/\R/', $appLog);
        foreach ($log as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $data = explode(';', $line);
            if (count($data) !== 2) continue;

            $id = trim($data[0]);
            $timestamp = trim($data[1]);

            if ($id == 0) {
                $startTime = $timestamp;
                continue;
            }

            if (!isset($racers[$id][0])) {
                $correction = $ridersName[$id]['correction'] ?? 0;
                $start = (float)$startTime + 1000 * (float)$correction;
                $racers[$id][0] = (string)$start;
            }

            $racers[$id][] = $timestamp;
        }

        $lapsData = $this->lapCalculator->calculateLaps($racers, $ridersName);

        uasort($lapsData, function ($a, $b) {
            if ($a['total_laps'] !== $b['total_laps']) {
                return $b['total_laps'] - $a['total_laps'];
            }
            return $a['total_time'] <=> $b['total_time'];
        });

        $res = json_encode($lapsData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $liveEvent->setResult($res);
        $liveEvent->setData(new \DateTime('now'));

        $this->entityManager->persist($liveEvent);
        $this->entityManager->flush();

        return 'OK';
    }



    public function searchRecord($result): array
    {
        $bestTime = false;
        $bestLapsAll = [];
        /** @var EventRider $rider */
        foreach ($result as  $rider) {
            if ($rider->getBestLapTime()) {
                $time = $this->formatter->parseTimeToMilliseconds($rider->getBestLapTime());
                if (!$bestTime || $time < $bestTime) {
                    $bestTime = $time;
                    $bestLapsAll = [
                        'riderId' => $rider->getNumber(),
                        'name' => $rider->getName(),
                        'class' => $rider->getClass(),
                        'bestLap' => $rider->getBestLapTime(),
                    ];
                }
            }
        }

        return $bestLapsAll;
    }

    private function genLiveId(): string
    {
        $id = rand(1111, 9999);
        $charList = ['a', 'b', 'c', 'd', 'e', 'r', 't', 'm', 'n'];
        return $charList[rand(0, count($charList) - 1)] . $id;
    }

    /**
     * @param $hash
     * @return Events|object|null
     */
    public function getEventByHash(string $hash): Events
    {
        $event =  $this->entityManager->getRepository(Events::class)->findOneBy(['hash'=>$hash]);
        if (!$event) {
            throw new NotFoundResourceException('Event not found');
        }
        return $event;
    }

    public function getLiveEventById(string $liveId): LiveEvents{
        $liveEvent = $this->entityManager->getRepository(LiveEvents::class)->findOneBy(['eventId'=>$liveId]);
        if(!$liveEvent){
            throw new NotFoundResourceException('Event not found');
        }
        return $liveEvent;
    }

    /**
     * @param Events $event
     * @return void
     */
    public function incrementEventsViews(Events  $event): void
    {
        $event->setCount($event->getCount() + 1);
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }
}
