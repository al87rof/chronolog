<?php

namespace App\Repository;

use App\Entity\Riders;
use App\Entity\RidersDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RidersDictionaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RidersDictionary::class);
    }

    public function searchRider(string $name): array
    {
        return $this->createQueryBuilder('rd')
            ->join('rd.rider', 'r')
            ->select('r.id AS r_id')
            ->where('rd.originalName LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getArrayResult();
    }
}
