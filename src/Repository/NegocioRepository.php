<?php

namespace App\Repository;

use App\Entity\Negocio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Negocio>
 */
class NegocioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Negocio::class);
    }

//    /**
//     * @return Negocio[] Returns an array of Negocio objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Negocio
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
//    public function findCatComida($negocioID): ?Negocio
//    {
//        return $this->createQueryBuilder('n')
//        ->innerJoin('n.CatComida', 'c')
//        ->addSelect('c')
//        ->andWhere('n.id = :val')
//         ->setParameter('val', $negocioID)
//         ->getQuery()
//         ->getOneOrNullResult()
//        ;
//    }
}
