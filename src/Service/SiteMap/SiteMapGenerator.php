<?php

namespace App\Service\SiteMap;

use App\Entity\Events;
use App\Entity\Riders;
use App\Service\RiderProvider\RiderResultFinder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class SiteMapGenerator
{

    /** @var EntityManagerInterface  */
    private EntityManagerInterface $entityManager;
    /** @var RouterInterface */
    private RouterInterface $router;
    /** @var RiderResultFinder  */
    private RiderResultFinder $riderResultFinder;

    public function __construct(EntityManagerInterface $entityManager,RouterInterface $router,RiderResultFinder $riderResultFinder)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->riderResultFinder = $riderResultFinder;
    }


    public function generateSiteMapXML(): string
    {
        $events = $this->entityManager->getRepository(Events::class)->findAll();
        $riders = $this->entityManager->getRepository(Riders::class)->findAll();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $mainPage = $this->router->generate('main_page',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $eventsPage = $this->router->generate('events',[],UrlGeneratorInterface::ABSOLUTE_URL);
        $ridersPage = $this->router->generate('riders',[],UrlGeneratorInterface::ABSOLUTE_URL);

        $xml .= '<url>';
        $xml .= '<loc>'.$mainPage.'</loc>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';


        $xml .= '<url>';
        $xml .= '<loc>'.$eventsPage.'</loc>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';


        $xml .= '<url>';
        $xml .= '<loc>'.$ridersPage.'</loc>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';


        /** @var Events $event */
        foreach ($events as $event) {
            $eventPage = $this->router->generate('result',['id'=>$event->getHash()],UrlGeneratorInterface::ABSOLUTE_URL);
            $xml .= '<url>';
            $xml .= '<loc>' . $eventPage . '</loc>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';

            $classes = $this->riderResultFinder->getAllClasses($event);

            foreach ($classes as $class){
                $class = trim($class);
                $lapChartPage = $this->router->generate('lapchart',['id'=>$event->getHash(),'filter'=>$class],UrlGeneratorInterface::ABSOLUTE_URL);
                $xml .= '<url>';
                $xml .= '<loc>' . $lapChartPage . '</loc>';
                $xml .= '<priority>0.5</priority>';
                $xml .= '</url>';
            }

        }

        /** @var Riders $rider */
        foreach ($riders as $rider){
            $riderPage = $this->router->generate('rider',['id'=>$rider->getId()],UrlGeneratorInterface::ABSOLUTE_URL);
            $xml .= '<url>';
            $xml .= '<loc>' . $riderPage . '</loc>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }


        $xml .= '</urlset>';


        return $xml;
    }

}
