<?php
/****************************************
   Fichier : EmpruntRepository.php
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
20 avril 2021 / Samuel / Ajouter fonction lstReservation pour recevoir la liste des réservations
20 avril 2021 / Samuel / Ajouter updateEtat, fonction qui permet de changer l’état d’une réservation
24 avril 2021 / Willaim / Ajouter fonction lstAllReservation pour recevoir la liste des réservations
 ****************************************/


namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    // /**
    //  * @return Emprunt[] Returns an array of Emprunt objects
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
    public function findOneBySomeField($value): ?Emprunt
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Fonction qui liste les reservations
     */
    public function lstReservation(){

        
        $listeReservation = $this->getEntityManager()
        ->createQuery(" SELECT e.id, e.qteActuelle, e.dateRetourPrevue, ee.nom AS Etat, ee.id AS idEtat, CONCAT(u.prenom,' ',u.nom) AS Etudiant, p.nom AS Pieces from App\Entity\Emprunt e
        JOIN App\Entity\EtatEmprunt ee WITH e.idEtat = ee.id
        JOIN App\Entity\Utilisateur u WITH  e.idUtilisateur = u.id
        JOIN App\Entity\Piece p WITH e.idPiece = p.id
        WHERE ee.nom != 'Terminer' ")
        ->getArrayResult();

        return $listeReservation;
    }

    public function lstAllReservation(){

        
        $listeReservation = $this->getEntityManager()
        ->createQuery(" SELECT e.id, e.qteActuelle, e.dateRetourPrevue, ee.nom AS Etat, ee.id AS idEtat, CONCAT(u.prenom,' ',u.nom) AS Etudiant, p.nom AS Pieces from App\Entity\Emprunt e
        JOIN App\Entity\EtatEmprunt ee WITH e.idEtat = ee.id
        JOIN App\Entity\Utilisateur u WITH  e.idUtilisateur = u.id
        JOIN App\Entity\Piece p WITH e.idPiece = p.id")
        ->getArrayResult();

        return $listeReservation;
    }
    /*
        Permet de changer l'état d'une commande
    */
    public function updateEtat($id,$etat){

        $this->getEntityManager()
        ->createQuery("UPDATE App\Entity\Emprunt e SET e.idEtat = $etat WHERE e.id = $id")->execute();
    }
    public function newEmprunt($idUser,$idPieces,$qqtPieces,$idSession,$todaym,$dateRetour){
        

        $this->getEntityManager()
        ->createQuery("INSERT into App\Entity\Emprunt e (idUtilisateur,idPiece,idSession,idEtat,dateDemande,dateRetourPrevue,QteInitiale,qteActuelle) VALUES ($idUser,$idPieces,)")->execute();
        
    }
}
