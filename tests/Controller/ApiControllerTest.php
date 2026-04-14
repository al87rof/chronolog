<?php

namespace App\Tests\Controller;


use App\Controller\ApiController;
use App\Service\Calculate\LapCalculator;
use App\Service\Calculate\LiveEventService;
use App\Service\Calculate\TimeFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ApiControllerTest extends KernelTestCase

{

    private LiveEventService $service;

    /**
     * @throws \ReflectionException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        // 1. Загружаем ядро Symfony
        self::bootKernel();

        // 2. Достаем настоящий EntityManager из контейнера
        $container = static::getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $timeFormatter = new TimeFormatter();
        $this->service = new LiveEventService($entityManager,new TimeFormatter(),new LapCalculator($timeFormatter));
    }


    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testUpdLiveEventsSuccess(){
        $contentAppLog = '{"liveId": "n5063","fileContent": "26;1774700343116\n18;1774700626841\n5;1774700632702\n21;1774700682996\n3;1774701088394\n14;1774701117158"}';
        $request = new Request( [],                                    // query параметры
            [],                                    // request параметры (POST)
            [],                                    // attributes
            [],                                    // cookies
            [],                                    // files
            [],                                    // server
            $contentAppLog);                          // контент в теле);
        $controller = new ApiController($this->service);
        $response = $controller->updLiveEventsAction($request);

        $this->assertInstanceOf(Response::class,$response);
    }


}
