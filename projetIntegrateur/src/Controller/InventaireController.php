<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Piece;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireController extends AbstractController
{
    /**
     * @Route("/inventaire", name="inventaire")
     */
    public function index()
    {

        $piecesrepository = $this->getDoctrine()->getManager()->getRepository(Piece::class);
        $lstPieces = $piecesrepository->lstPieceCategorie();

        $categorierepository = $this->getDoctrine()->getManager()->getRepository(Categorie::class);
        $lstCategorie = $categorierepository->findAll();

        return $this->render('inventaire/index.html.twig', [
            'controller_name' => 'InventaireController',
            'pieces' => $lstPieces,
            'categories' => $lstCategorie,
        ]);
    }

     /**
     * @Route("/chanqueQTETotal", name="chanqueQTETotal")
     */
    public function changeQTETotal(Request $r): Response
    {
        $info = $r->request->all();
        $response = new Response();

        if(!preg_match('/^0*[0-9]\d*$/',$info['qte'])){
            $response->setContent("fail");
            return $response;
        }


        $em = $this->getDoctrine()->getManager();
        $piece = $em->getRepository(piece::class)->findOneBy(['id' => $info['id']]);
        $piece->setQteTotal($info['qte']);
        $em->flush();
        return $response;
    }

}
