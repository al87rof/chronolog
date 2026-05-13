<?php

namespace App\Service\RiderProvider;


use App\Entity\Events;
use App\Entity\Riders;
use App\Entity\RidersDictionary;
use App\Repository\RidersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RiderCollector
{

    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var RouterInterface */
    private $router;
    /**
     * @var RiderResultFinder
     */
    private $riderResultFinder;

    public function __construct(EntityManagerInterface $entityManager,RouterInterface $router,RiderResultFinder $riderResultFinder)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->riderResultFinder = $riderResultFinder;
    }


    public function collectAllRiders(): array
    {
        $events = $this->entityManager->getRepository(Events::class)->findAll();
        $result = [];
        /** @var Events $event */
        foreach ($events as $event){
           $riders =  $event->getRiderList();
           $r =  $this->readCsv($riders);

          $result =  array_merge($r,$result);
        }

        $this->saveRiders($result);
        return $result;

    }



    private function saveRiders($riders){
        foreach ($riders as $rider){
            if($this->isCyrillic($rider)){
                $rider = str_replace(" (мол)",'(мол)', $rider);
                $riderData =  explode(" ",$rider);

                if(count($riderData) == 1){
                    if(!$this->isIsset($rider)){
                        echo " $rider \n";
                    }
                    continue;
                }

                $rider = $riderData[0].' '.$riderData[1];



                $variants = [
                    "$riderData[0] $riderData[1]",
                    "$riderData[1] $riderData[0]",
                ];


                $isIsset = true;

                foreach ($variants as $query){

                    if(!$this->isIsset($query)){
                        $isIsset = false;
                        break;
                    }
                }

                $this->entityManager->beginTransaction();

                try {

                    if (!$isIsset) {

                        $rr = new Riders();
                        $rr->setTeam('');
                        $rr->setEventsIds('');
                        $rr->setName($rider);
                        $this->entityManager->persist($rr);

                        foreach ($variants as $query) {
                            $this->save($query, $rr);
                        }

                        $this->entityManager->flush();
                        echo "ADD NEW RIDER : {$rr->getId()} | $rider\n";
                    }

                    $this->entityManager->commit();

                } catch (\Throwable $e) {

                    $this->entityManager->rollback();

                    throw $e;
                }


            }else{

                if(!$this->isIsset($rider)){
                    echo " $rider \n";
                }
            }
        }
    }


    private function isIsset($query){
        $result = $this->entityManager->getRepository(RidersDictionary::class)->searchRider($query);
        return (bool)$result;
    }

    private function save($rider,$rr){
        $res = $this->entityManager->getRepository(RidersDictionary::class)->searchRider($rider);

        if(!$res){
            $r = new RidersDictionary();
            $r->setOriginalName($rider);
            $r->setNormalizedName('');
            $r->setRider($rr);
            $this->entityManager->persist($r);
        }
    }



   private function isCyrillic($text) {

       $text = str_replace("'"," ",$text);
       $text = str_replace("("," ",$text);
       $text = str_replace(")"," ",$text);
        // Проверяем, что строка состоит только из кириллицы (а-яА-ЯёЁ)
       $result = preg_match('/^[А-Яа-яЁёІіЇїЄєҐґ\s-]+$/u', $text);

        return $result;
    }



    private function  readCsv($ridersContent){
        $lines = explode("\n", $ridersContent);
        foreach ($lines as $line) {
            $parts = explode(';', $line);
            if (count($parts) < 3) continue;
            $id = trim($parts[0]);
            $class = trim($parts[1]);
            $name = trim($parts[2]);
            $allRiders[] = $name;
        }

        return $allRiders;
    }


    public function getRiders(){
        return$this->entityManager->getRepository(Riders::class)->findAll();
    }



    public function searchEventsByRiderId($rider): array
    {
        $riderDic = $this->entityManager
            ->getRepository(RidersDictionary::class)
            ->findBy(['rider' => $rider]);

        $variants = [];
        /** @var RidersDictionary $item */
        foreach ($riderDic as $item) {
            $variants[] = $item->getOriginalName();
        }

        $eventsIds = [];
        foreach ($variants as $variant) {
            $eventsTmp = $this->entityManager
                ->getRepository(Events::class)
                ->searchEventByRider($variant);
            $eventsIds = array_merge($eventsIds, $eventsTmp);
        }

        $events = $this->entityManager
            ->getRepository(Events::class)
            ->findBy(['id' => $eventsIds], ['date' => 'DESC']);

        $mergedEvents  = [];
        $eventsRiders  = [];
        $positions     = [];
        $statStarts    = 0;
        $statFinishes  = 0;

        /** @var Events $event */
        foreach ($events as $event) {
            $statStarts++;

            $eventRider = $this->riderResultFinder->findEventRider($event, $variants, null);

            $mergedEvents[] = $event;
            $eventsRiders[] = $eventRider;

            if ($eventRider->getStatus() === 'FINISHED') {
                $statFinishes++;
                $positions[] = $eventRider->getPosition();
            }

            if ($this->riderResultFinder->isMultiClasses($event, $variants)) {
                $classes = $this->riderResultFinder->getRiderClasses($event, $variants);
                $filter  = $classes[1];

                $subEvent      = clone $event;
                $subEventRider = $this->riderResultFinder->findEventRider($event, $variants, $filter);

                $mergedEvents[] = $subEvent;
                $eventsRiders[] = $subEventRider;

                if ($subEventRider->getStatus() === 'FINISHED') {
                    $statFinishes++;
                    $positions[] = $subEventRider->getPosition();
                }
            }
        }

        $average = count($positions) > 0
            ? floor(round(array_sum($positions) / count($positions), 1))
            : '-';

        $finishPercent = $statStarts
            ? ($statFinishes / $statStarts) * 100
            : 0;

        return [
            'events'        => $mergedEvents,
            'eventsRiders'  => $eventsRiders,
            'starts'        => $statStarts,
            'finishes'      => $statFinishes,
            'avgPosition'   => $average,
            'finishPercent' => round($finishPercent, 2),
        ];
    }


    public function getRider($riderId){
        return $this->entityManager->getRepository(Riders::class)->find($riderId);
    }

    /**
     * @param $name
     * @return string
     */
    public function getRiderUrlByName($name): string{
        /** @var Riders $rider */
        $rider = $this->getRiderByName($name);
        if($rider instanceof Riders){
            return $this->router->generate('rider',['id'=>$rider->getId()], UrlGeneratorInterface::ABSOLUTE_PATH);
        }
        return "#";
    }


    public function getRiderByName($name): Riders|string {
        $riderData = explode(" ",$name);
        $first = $riderData[0] ?? ' ';
        $second = $riderData[1] ?? ' ';
        $rider =  $this->entityManager->getRepository(Riders::class)->searchRiderV2($first." ".$second);
        return $rider ?: '#';
    }

}
