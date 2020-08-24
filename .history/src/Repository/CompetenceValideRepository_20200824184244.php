<?php

namespace App\Repository;

use App\Entity\CompetenceValide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetenceValide|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenceValide|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenceValide[]    findAll()
 * @method CompetenceValide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceValideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetenceValide::class);
    }

    // /**
    //  * @return CompetenceValide[] Returns an array of CompetenceValide objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOneBySomeField($id_promotion,$id_referentiel, $): ?CompetenceValide
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.promotion = :id_promotion')
            ->andWhere('c.referentiel = :id_referentiel')
            ->andWhere('c.apprenant = :id_apprenant')
            ->andWhere('c.competence = :id_competence')
            ->setParameter('id_promotion', $id_promotion)
            ->setParameter('id_referentiel', $id_referentiel)
            ->setParameter('id_apprenant', $id_apprenant)
            ->setParameter('id_competence', $id_competence)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
