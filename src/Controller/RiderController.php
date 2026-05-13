<?php

namespace App\Controller;


use App\Service\RiderProvider\RiderCollector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class RiderController extends AbstractController
{
    public function __construct(private readonly RiderCollector $ridersCollector)
    {
    }

    public function riderAction(Request $request,$id)
    {


        $rider = $this->ridersCollector->getRider($id);

        if(!$rider){
           throw new \Exception('Not Found');
        }

        $riderData = $this->ridersCollector->searchEventsByRiderId($rider);

        $params  = [
            'rider'=>$rider,
            'eventsRiders'=>$riderData['eventsRiders'],
            'events'=>$riderData['events'],
            'starts'=>$riderData['starts'],
            'finishes'=>$riderData['finishes'],
            'avgPosition'=> $riderData['avgPosition'],
            'finishPercent'=>$riderData['finishPercent']
            ];

        return $this->render('rider/rider.html.twig', $params);
    }


    public function ridersAction(Request $request){

       $params = [
            'riders'=>$this->ridersCollector->getRiders()
       ];

       return $this->render('riders/riders.html.twig', $params);
    }

}
