<?php

namespace App\Repository;

use App\Entity\LivrableApprenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivrableApprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivrableApprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivrableApprenant[]    findAll()
 * @method LivrableApprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrableApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivrableApprenant::class);
    }

    // /**
    //  * @return LivrableApprenant[] Returns an array of LivrableApprenant objects
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
    public function findOneBySomeField($value): ?LivrableApprenant
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
