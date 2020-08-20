<?php

namespace App\Repository;

use App\Entity\BriefPromotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BriefPromotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method BriefPromotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method BriefPromotion[]    findAll()
 * @method BriefPromotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefPromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BriefPromotion::class);
    }

    // /**
    //  * @return BriefPromotion[] Returns an array of BriefPromotion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BriefPromotion
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
