<?php

/****************************************
   Fichier : PieceRepository.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   25 avril 2021    Approuvé par l'équipe
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
    20 avril 2021 / Samuel / fonction lstPieceCategorie Fait la liste des pieces avec le nom de la categorie pour l’inventaire
    22 avril        P-À		Ajout d’une fonction update la quantité de loué
 ****************************************/


namespace App\Repository;

use App\Entity\Piece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Piece|null find($id, $lockMode = null, $lockVersion = null)
 * @method Piece|null findOneBy(array $criteria, array $orderBy = null)
 * @method Piece[]    findAll()
 * @method Piece[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PieceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Piece::class);
    }

    // /**
    //  * @return Piece[] Returns an array of Piece objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Piece
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * Fonction qui liste les attributs de pieces en plus du nom de la catégorie qu'il sont associés
     */
    public function lstPieceCategorie(){

        $listeProduit = $this->getEntityManager()
        ->createQuery("SELECT p,c.nom AS categorie FROM App\Entity\Piece p , App\Entity\Categorie c WHERE p.idCategorie = c.id")
        ->getArrayResult();

        return $listeProduit;
    }
    public function updateQte($id,$qtt){


        $this->getEntityManager()
        ->createQuery("UPDATE App\Entity\Piece p SET p.QteEmprunter = $qtt WHERE p.id = $id")->execute();
    }

    
}
