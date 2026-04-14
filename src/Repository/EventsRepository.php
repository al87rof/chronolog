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

}
