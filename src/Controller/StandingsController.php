<?php

namespace App\Controller;


use App\Model\Standings\StandingsTableDto;
use App\Service\Calculate\StandingsService;
use App\Service\RiderProvider\RiderCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class StandingsController extends AbstractController
{
    public function __construct(private StandingsService $standingsService, private  RiderCollector $riderCollector)
    {
    }


    public function standingsYearAction(Request $request, $year)
    {

        $yearsFilter = $this->standingsService->getAvailableYears();
        $classes = $this->standingsService->getAvailableClasses();

        $params = [
            'yearsFilter' => $yearsFilter,
            'classes' => $classes,
            'year' => $year
        ];

        return $this->render('standings/standings_main.html.twig', $params);
    }


    public function standingsYearClassAction(Request $request, $year, $class)
    {
        /** @var StandingsTableDto $result */
        $result = $this->standingsService->buildStandings($class, $year);

        $params = [
            'table' => $result,
            'year' => $year,
            'class' =>$class,
            'riderCollector' => $this->riderCollector
        ];

        return $this->render('standings/standing_by_class.html.twig', $params);
    }
}
