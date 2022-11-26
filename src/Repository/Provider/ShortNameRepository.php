<?php

namespace App\Repository\Provider;

use App\Entity\Provider\ShortName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShortName|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortName|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortName[]    findAll()
 * @method ShortName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortName::class);
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
