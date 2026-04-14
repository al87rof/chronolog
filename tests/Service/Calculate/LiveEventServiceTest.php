<?php

namespace App\Tests\Service\Calculate;

use App\Model\EventRider;
use App\Service\Calculate\LapCalculator;
use App\Service\Calculate\LiveEventService;
use App\Service\Calculate\TimeFormatter;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class LiveEventServiceTest extends TestCase
{
    private LiveEventService $service;

    /**
     * @throws ReflectionException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $timeFormatter = new TimeFormatter();
        $this->service = new LiveEventService($entityManager,new TimeFormatter(),new LapCalculator($timeFormatter));
    }

    public function testSearchRecord(){

        $eventRider = new EventRider();
        $eventRider->setBestLapTime('00:00:15:000');
        $eventRider->setClass('Free');
        $eventRider->setName('Oleg');
        $eventRider->setNumber('1');

        $eventRider2 = new EventRider();
        $eventRider2->setBestLapTime('00:00:11:000');
        $eventRider2->setClass('Free');
        $eventRider2->setName('Anton');
        $eventRider2->setNumber('2');


        $eventRider3 = new EventRider();
        $eventRider3->setBestLapTime('00:00:11:200');
        $eventRider3->setClass('Free');
        $eventRider3->setName('Alex');
        $eventRider3->setNumber('3');

        $racersResult = [
            $eventRider,
            $eventRider2,
            $eventRider3
        ];

        $result = $this->service->searchRecord($racersResult);

        $this->assertSame('Free',$result['class']);
        $this->assertSame('2',$result['riderId']);
        $this->assertSame('00:00:11:000',$result['bestLap']);
        $this->assertSame('Anton',$result['name']);
    }

    public function testSearchRecordReturnsEmpty(): void
    {
        $result = $this->service->searchRecord([]);

        $this->assertSame([], $result);
    }
}
