<?php

namespace App\Repository;

use App\Entity\Competitions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competitions>
 */
class CompetitionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competitions::class);
    }
    public function searchByQuery(string $query): array
{
    return $this->createQueryBuilder('c')
        ->where('c.name LIKE :query')
        ->orWhere('c.type LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
public function countByType(string $type): int
{
    return $this->createQueryBuilder('c')
        ->select('COUNT(c)')
        ->where('c.type = :type')
        ->setParameter('type', $type)
        ->getQuery()
        ->getSingleScalarResult();  
}
public function countAll(): int
{
    return $this->createQueryBuilder('c')
        ->select('COUNT(c)')
        ->getQuery()
        ->getSingleScalarResult();
}
public function countByTypeGrouped(): array
{
    return $this->createQueryBuilder('c')
        ->select('c.type, COUNT(c.id) AS count')
        ->groupBy('c.type')
        ->orderBy('count', 'DESC')
        ->getQuery()
        ->getResult();
}
public function findMartialArtCompetitions(): array
{
    return $this->createQueryBuilder('c')
        ->join('c.typeCompetition', 't')
        ->andWhere('t.name = :typeName')
        ->setParameter('typeName', 'Martial Art')
        ->getQuery()
        ->getResult();
}

public function findNonMartialArtCompetitions(): array
{
    return $this->createQueryBuilder('c')
        ->join('c.typeCompetition', 't')
        ->andWhere('t.name = :typeName')
        ->setParameter('typeName', 'Non Martial Art')
        ->getQuery()
        ->getResult();
}


public function findByType(string $type): array
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.type = :type')
        ->setParameter('type', $type)
        ->getQuery()
        ->getResult();
}






    //    /**
    //     * @return Competitions[] Returns an array of Competitions objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Competitions
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
