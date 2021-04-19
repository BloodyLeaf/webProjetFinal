<?php

namespace App\Repository;

use App\Entity\CycleVieEmprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CycleVieEmprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method CycleVieEmprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method CycleVieEmprunt[]    findAll()
 * @method CycleVieEmprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CycleVieEmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CycleVieEmprunt::class);
    }

    // /**
    //  * @return CycleVieEmprunt[] Returns an array of CycleVieEmprunt objects
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

    /*
    public function findOneBySomeField($value): ?CycleVieEmprunt
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
