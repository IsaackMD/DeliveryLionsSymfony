<?php

namespace App\Repository;

use App\Entity\Pedido;
use App\Entity\PedidoMenu;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pedido>
 */
class PedidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pedido::class);
    }

//    /**
//     * @return Pedido[] Returns an array of Pedido objects
//     */
public function findById(User $value, int $opc): array
{
    $query = $this->createQueryBuilder('p')
        ->leftJoin('p.pedidoMenus', 'pd')
        ->leftJoin('pd.Menu', 'm')
        ->andWhere('p.Usuario = :usuario')
        ->setParameter('usuario', $value);

    if ($opc === 1) {
        $query->andWhere('p.Estatus = :estatusPendiente')
              ->andWhere('pd.Estatus = :estatusPd')
              ->setParameter('estatusPendiente', 'Pendiente')
              ->setParameter('estatusPd', true);
    } elseif ($opc === 2) {
        $query->andWhere('p.Estatus = :estatusConfirmado')
              ->setParameter('estatusConfirmado', 'Confirmado');
    }

    return $query
        ->addSelect('pd', 'm')
        ->orderBy('p.id', 'ASC')
        ->getQuery()
        ->getResult();
}




//    public function findOneBySomeField($value): ?Pedido
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
