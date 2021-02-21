<?php

namespace App\Repository;

use App\Entity\UserStocks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserStocks|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStocks|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStocks[]    findAll()
 * @method UserStocks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStocksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStocks::class);
    }

    // /**
    //  * @return UserStocks[] Returns an array of UserStocks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserStocks
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
