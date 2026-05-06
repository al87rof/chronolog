<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    public function searchEventByRider(string $query): array
    {
        $result = $this->createQueryBuilder('e')
            ->select('e.id')
            ->where('e.riderList LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getArrayResult();

        return array_column($result, 'id');
    }

    public function getEventsForStandings(\DateTimeInterface $date): array
    {
        $start = new \DateTimeImmutable($date->format('Y') . '-01-01 00:00:00');
        $end = $start->modify('+1 year');

        return $this->createQueryBuilder('e')
            ->where('e.isOfficial = :official')
            ->andWhere('e.date >= :start')
            ->andWhere('e.date < :end')
            ->setParameter('official', true)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }


    public function getAvailableYears(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "    SELECT DISTINCT YEAR(date) AS year
                    FROM events
                    ORDER BY year DESC
                ";

        $result = $conn->executeQuery($sql)->fetchFirstColumn();

        return array_map('intval', $result);
    }

}
