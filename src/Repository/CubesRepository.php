<?php

namespace App\Repository;

use App\Entity\Cubes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cubes>
 */
class CubesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cubes::class);
    }

    //    /**
    //     * @return Cubes[] Returns an array of Cubes objects
    //     */
       public function findLastCubeId(int $value = 4): array
       {
           return $this->createQueryBuilder('c')
            //    ->andWhere('c.exampleField = :val')
            //    ->setParameter('val', $value)
               ->orderBy('c.id', 'DESC')
               ->setMaxResults($value)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Cubes
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
