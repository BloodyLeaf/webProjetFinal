<?php

namespace App\Controller;

use App\Entity\Piece;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireController extends AbstractController
{
    /**
     * @Route("/inventaire", name="inventaire")
     */
    public function index(): Response
    {

        $piecesrepository = $this->getDoctrine()->getManager()->getRepository(Piece::class);
        $lstPieces = $piecesrepository->findAll();

        return $this->render('inventaire/index.html.twig', [
            'controller_name' => 'InventaireController',
            'pieces' => $lstPieces,
        ]);
    }

}
