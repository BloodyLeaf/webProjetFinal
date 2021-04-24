<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Piece;
use App\Entity\Utilisateur;
use App\Entity\Emprunt;
use App\Entity\EtatEmprunt;
use App\Entity\IncidentEmprunt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }
    /**
     * @Route("/reportpiece", name="report_piece")
     */
    public function reportPiece()
    {
        $piecesrepository = $this->getDoctrine()->getManager()->getRepository(Piece::class);
        $lstPieces = $piecesrepository->lstPieceCategorie();

        $categorierepository = $this->getDoctrine()->getManager()->getRepository(Categorie::class);
        $lstCategorie = $categorierepository->findAll();

        return $this->render('report/reportPiece.html.twig', [
            'controller_name' => 'ReportController',
            'pieces' => $lstPieces,
            'categories' => $lstCategorie,
        ]);
    }
    /**
     * @Route("/reportutilisateur", name="report_utilisateur")
     */
    public function reportUtilisateur()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateursRepository = $em->getRepository(Utilisateur::class);

        $listeUtilisateurs = $utilisateursRepository->findAll();
        return $this->render('report/reportUtilisateur.html.twig', [
            'controller_name' => 'ReportController',
            'utilisateurs' => $listeUtilisateurs
            ]);
    }
    /**
     * @Route("/reportemprunt", name="report_emprunt")
     */
    public function reportemprunt()
    {
        $reservationrepository = $this->getDoctrine()->getManager()->getRepository(Emprunt::class);
        $lstReservation = $reservationrepository->lstAllReservation();

        $etatEmpruntrepository = $this->getDoctrine()->getManager()->getRepository(EtatEmprunt::class);
        $lstEtat = $etatEmpruntrepository->findAll();

        return $this->render('report/reportEmprunt.html.twig', [
            'controller_name' => 'ReportController',
            'reservations' => $lstReservation,
            'etats' => $lstEtat,
        ]);
    }
    
}
