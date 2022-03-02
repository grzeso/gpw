<?php

namespace App\Repository;

use App\Entity\Log;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    // /**
    //  * @return Log[] Returns an array of Log objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Log
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByTsAndEventId(DateTime $date, int $eventId)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.ts >= :dateStart')
            ->andWhere('l.ts <= :dateEnd')
            ->andWhere('l.eventId = :eventId')
            ->setParameter('dateStart', $date->setTime(0, 0))
            ->setParameter('dateEnd', $date->setTime(23, 59, 59))
            ->setParameter('eventId', $eventId)
            ->getQuery()
            ->getResult()
            ;
    }
}
