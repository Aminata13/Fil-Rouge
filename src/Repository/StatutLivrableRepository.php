<?php

namespace App\Repository;

use App\Entity\StatutLivrable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutLivrable|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutLivrable|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutLivrable[]    findAll()
 * @method StatutLivrable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutLivrableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutLivrable::class);
    }

    // /**
    //  * @return StatutLivrable[] Returns an array of StatutLivrable objects
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
    public function findOneBySomeField($value): ?StatutLivrable
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
