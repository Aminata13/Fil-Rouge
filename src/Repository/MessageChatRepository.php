<?php

namespace App\Repository;

use App\Entity\MessageChat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageChat|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageChat|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageChat[]    findAll()
 * @method MessageChat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageChat::class);
    }

    // /**
    //  * @return MessageChat[] Returns an array of MessageChat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageChat
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
