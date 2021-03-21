<?php

namespace App\Repository;

use App\Entity\DaysWithoutSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DaysWithoutSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method DaysWithoutSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method DaysWithoutSession[]    findAll()
 * @method DaysWithoutSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DaysWithoutSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DaysWithoutSession::class);
    }

    // /**
    //  * @return DaysWithoutSession[] Returns an array of DaysWithoutSession objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DaysWithoutSession
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
