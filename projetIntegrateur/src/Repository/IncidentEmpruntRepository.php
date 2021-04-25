<?php
/****************************************
   Fichier : IncidentEmpruntRepository.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
 ****************************************/

namespace App\Repository;

use App\Entity\IncidentEmprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IncidentEmprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncidentEmprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncidentEmprunt[]    findAll()
 * @method IncidentEmprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidentEmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncidentEmprunt::class);
    }

    // /**
    //  * @return IncidentEmprunt[] Returns an array of IncidentEmprunt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IncidentEmprunt
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
