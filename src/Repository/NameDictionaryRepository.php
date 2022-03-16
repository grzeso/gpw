<?php

namespace App\Repository;

use App\Entity\NameDictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NameDictionary|null find($id, $lockMode = null, $lockVersion = null)
 * @method NameDictionary|null findOneBy(array $criteria, array $orderBy = null)
 * @method NameDictionary[]    findAll()
 * @method NameDictionary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NameDictionaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NameDictionary::class);
    }

    // /**
    //  * @return NameDictionary[] Returns an array of NameDictionary objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NameDictionary
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
