<?php

namespace App\Repository;

use App\Entity\InvolvedEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InvolvedEvents>
 */
class InvolvedEventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvolvedEvents::class);
    }
    public function findByParticipation(bool $isParticipating): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.isParticipating = :isParticipating')
            ->setParameter('isParticipating', $isParticipating)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return InvolvedEvents[] Returns an array of InvolvedEvents objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InvolvedEvents
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
