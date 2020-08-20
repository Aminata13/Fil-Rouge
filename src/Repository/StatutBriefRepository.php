<?php

namespace App\Repository;

use App\Entity\StatutBrief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutBrief|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutBrief|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutBrief[]    findAll()
 * @method StatutBrief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutBriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutBrief::class);
    }

    // /**
    //  * @return StatutBrief[] Returns an array of StatutBrief objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatutBrief
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
