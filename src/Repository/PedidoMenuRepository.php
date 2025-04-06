<?php

namespace App\Repository;

use App\Entity\PedidoMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PedidoMenu>
 */
class PedidoMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PedidoMenu::class);
    }

//    /**
//     * @return PedidoMenu[] Returns an array of PedidoMenu objects
//     */
   public function findPedidos($user): array
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.Usuario = :user')
           ->setParameter('user', $user)
           ->orderBy('p.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?PedidoMenu
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
