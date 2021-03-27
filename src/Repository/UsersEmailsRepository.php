<?php

namespace App\Repository;

use App\Entity\UsersEmails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsersEmails|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersEmails|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersEmails[]    findAll()
 * @method UsersEmails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersEmailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersEmails::class);
    }

    // /**
    //  * @return UsersEmails[] Returns an array of UsersEmails objects
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
    public function findOneBySomeField($value): ?UsersEmails
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
