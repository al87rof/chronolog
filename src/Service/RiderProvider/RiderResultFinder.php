<?php

namespace App\Service\RiderProvider;

use App\Entity\Events;
use App\Model\EventRider;

class RiderResultFinder
{

    /**
     * @param Events $event
     * @param array $variants
     * @param string|null $filter
     * @return array|EventRider
     */
    public function findEventRider(Events $event,  array $variants, string $filter = null): EventRider
    {
        $eventRider = new EventRider();
        $eventRider->setClass($this->getRiderClass($event,$variants,$filter));
        $eventRider->setNumber($this->getRiderNumber($event,$variants,$filter));
        $eventRider->setPosition((int)$this->getRiderPosition($event,$variants,$filter));
        $eventRider->setStatus($this->getRiderStatus($event,$variants));
        $eventRider->setAllClasses($this->getAllClasses($event));

        return  $eventRider;
    }

    public function getRiderClass(Events $event,$variants,$filter){

        if($filter){
            return $filter;
        }else{
            if($variants){
                foreach ($variants as  $rider){
                    $results = $event->getResultsJson();
                    foreach ($results as $row){

                        $rider = $this->filterSpecialChars($rider);
                        $rider2 = $this->filterSpecialChars($row['name']);

                        if(preg_match("~".$rider."~",$rider2)){
                            return $row['class'];
                        }
                    }
                }
            }
        }
    }


    public function getRiderClasses(Events $event,$variants): array
    {
        $classes = [];
        if($variants){
            foreach ($variants as  $rider){
                $results = $event->getResultsJson();
                foreach ($results as $row){

                    $rider = $this->filterSpecialChars($rider);
                    $rider2 = $this->filterSpecialChars($row['name']);

                    if(preg_match("~".$rider."~",$rider2)){
                        $classes[] = $row['class'];
                    }
                }
            }
        }
        return array_unique($classes);
    }

    public function isMultiClasses(Events $event,$variants): bool
    {
        return !(count($this->getRiderClasses($event, $variants)) <= 1);
    }

    public function getRiderNumber(Events $event,$variants,$filter):int
    {
        if($variants){
            foreach ($variants as  $rider){
                $results = $event->getResultsJson();
                foreach ($results as $number => $row){

                    $rider = $this->filterSpecialChars($rider);
                    $rider2 = $this->filterSpecialChars($row['name']);

                    if($filter){
                        if($filter === $row['class'] && preg_match("~".$rider."~",$rider2)){
                            return $number;
                        }
                    }else{
                        if(preg_match("~".$rider."~",$rider2)){
                            return $number;
                        }
                    }

                }
            }
        }
        return 0;
    }

    public function getRiderStatus(Events $event,$variants){
        if($variants){
            $DSQ = $event->getRidersListDsqArray();
            foreach ($variants as  $rider){
                $results = $event->getResultsJson();
                foreach ($results as $number => $row){

                    $rider = $this->filterSpecialChars($rider);
                    $rider2 = $this->filterSpecialChars($row['name']);

                    if(preg_match("~".$rider."~",$rider2)){
                        if(in_array((string)$number,$DSQ)){
                            return 'DSQ';
                        }else{
                            return $row['status'];
                        }
                    }
                }
            }
        }
    }


    public function getRiderPosition(Events $event,$variants,$class){
        if($variants){
            $result = $event->getResultsJson();
            $selectedFilter = $this->getRiderClass($event,$variants,$class);
            $riderNumber = $this->getRiderNumber($event,$variants,$class);
            $status = $this->getRiderStatus($event,$variants);

            if($status !== 'FINISHED'){
                return ' - ';
            }

            foreach ($result as $key =>$item){

                if($selectedFilter != 'all'){
                    if($item['class'] != $selectedFilter){
                        unset($result[$key]);
                    }
                }
            }

            $keys =  array_keys($result);

            return array_search($riderNumber,$keys) + 1;
        }
    }


    private function filterSpecialChars($text){
        if(in_array($text,["Штефура Михайло","Михайло Штефура"])){
            return 'штеф';
        }elseif (in_array($text,["Штефура Михайло (мол)","Михайло Штефура (мол)"])){
            return 'tef';
        }
        return $text;
    }

    public function getAllClasses(Events $event): array
    {
        $classes = [];

        $rows = array_map('str_getcsv', explode(PHP_EOL, $event->getRiderList()));
        if (empty($rows)) {
            return [];
        }

        foreach ($rows as $row){
            if(isset($row[0])){
                $data = explode(';',$row[0]);
                if(isset($data[1]) && !in_array($data[1],$classes)){
                    $classes[] = $data[1];
                }
            }

        }

        return  $classes;
    }
}
