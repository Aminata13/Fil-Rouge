<?php

namespace App\Repository;

use App\Entity\UserProfils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserProfils|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfils|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfils[]    findAll()
 * @method UserProfils[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfilsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfils::class);
    }

    // /**
    //  * @return UserProfils[] Returns an array of UserProfils objects
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
    public function findOneBySomeField($value): ?UserProfils
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
