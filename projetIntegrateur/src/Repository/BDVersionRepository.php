<?php

namespace App\Repository;

use App\Entity\BDVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BDVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method BDVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method BDVersion[]    findAll()
 * @method BDVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BDVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BDVersion::class);
    }

    // /**
    //  * @return BDVersion[] Returns an array of BDVersion objects
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
    public function findOneBySomeField($value): ?BDVersion
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getLatestBDVersion(){
        $bdVersionID = $this->getEntityManager()
        ->createQuery("SELECT b.id FROM App\Entity\BDVersion b ORDER BY b.timestamp DESC ")->setMaxResults(1)
        ->getArrayResult();
       
        return $bdVersionID;
        
    }
    
}
