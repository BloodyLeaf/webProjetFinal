<?php
/****************************************
   Fichier : EtatEmpruntRepository.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   Historique de modifications :
   Date           	Nom               	Description
   =============================================
    ****************************************/
namespace App\Repository;

use App\Entity\EtatEmprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtatEmprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatEmprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatEmprunt[]    findAll()
 * @method EtatEmprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatEmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatEmprunt::class);
    }

    // /**
    //  * @return EtatEmprunt[] Returns an array of EtatEmprunt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtatEmprunt
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
