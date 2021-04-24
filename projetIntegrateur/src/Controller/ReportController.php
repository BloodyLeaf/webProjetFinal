<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Piece;
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
}
