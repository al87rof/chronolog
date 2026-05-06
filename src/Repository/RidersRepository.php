<?php

namespace App\Repository;

use App\Entity\Riders;
use App\Entity\RidersDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RidersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Riders::class);
    }


    public function searchRiderV2(string $name): ?Riders
    {
        return $this->createQueryBuilder('r')
            ->innerJoin(
                'App\Entity\RidersDictionary',
                'rd',
                'WITH',
                'rd.riderId = r.id'
            )
            ->where('rd.originalName LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
