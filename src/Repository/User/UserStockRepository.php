<?php

namespace App\Repository\User;

use App\Entity\User\UserStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStock[]    findAll()
 * @method UserStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStock::class);
    }

    // /**
    //  * @return Stocks[] Returns an array of Stocks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stocks
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
