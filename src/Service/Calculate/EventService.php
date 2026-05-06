<?php

namespace App\Service\Calculate;

use App\Entity\Events;
use App\Interfaces\Service\ReaderInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class EventService
{
    private EntityManagerInterface $entityManager;
    private LapCalculator $lapCalculator;
    private ReaderInterface $reader;

    public function __construct(EntityManagerInterface $entityManager, LapCalculator $lapCalculator,ReaderInterface  $reader)
    {
        $this->entityManager = $entityManager;
        $this->lapCalculator = $lapCalculator;
        $this->reader = $reader;
    }

    public function getEvents(): array
    {
        return $this->entityManager->getRepository(Events::class)->findBy([], ['date' => 'DESC']);
    }

    public function processRaceResults($file, $file2): array|string
    {
        if (!file_exists($file) && !file_exists($file2)) {
            return "File not found.";
        }

        $startTime = 0;
        $racers = [];
        $ridersName = $this->parseRiderName($file2);

        foreach ($this->reader->read($file) as $line){
            $data = explode(";", $line);
            if (count($data) !== 2) continue;

            $id = trim($data[0]);
            $timestamp = trim($data[1]);

            if ($id == 0) {
                $startTime = $timestamp;
                continue;
            }

            if (!isset($racers[$id][0])) {
                $correction = $ridersName[$id]['correction'];
                $start = (float)$startTime + (float)1000 * $correction;
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

        return $lapsData;
    }

    public function save($file, $file2, $eventImg, $eventName, $result): bool|string
    {
        /** @var Connection $connection */
        $connection = $this->entityManager->getConnection();
        $hash = md5(json_encode($result));
        $event = $this->entityManager->getRepository(Events::class)->findOneBy(["hash" => $hash]);
        try{
            $connection->beginTransaction();
            if (!$event) {
                $event = new Events();
                $event->setDate(new \DateTime('now'));
                $event->setAppLog(file_get_contents($file2));
                $event->setName($eventName);
                $event->setRiderList(file_get_contents($file));
                $event->setEventImg($eventImg);
                $event->setResults(json_encode($result));
                $event->setHash($hash);
                $event->setCount(1);
                $event->setDescription('');
                $event->setSearchTags('');
                $event->setTypeId(1);
                $event->setRidersListDsq('');
                $event->setIsOfficial(0);

                $this->entityManager->persist($event);
                $this->entityManager->flush();
                // End Transaction
                $connection->commit();
                return $hash;
            }
        }catch (\Exception $exception) {
            // todo
            $connection->rollBack();
        }

        return false;
    }


    private function parseRiderName($file)
    {
        $riders = [];
        /** @var string $line */
        foreach ($this->reader->read($file) as $line){
            $data = explode(";", $line);
            $id = trim($data[0]);
            $class = trim($data[1]);
            $name = trim($data[2]);
            $correction = isset($data[3]) ? trim($data[3]) : 0;
            $riders[$id] = ['name' => $name, 'class' => $class, 'correction' => (int)$correction];
        }
        return $riders;
    }
}
