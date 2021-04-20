<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Piece;

class ApiMobileController extends AbstractController
{
    /**
     * @Route("/api/mobile/inventaire", name="api_mobile", methods={"POST"})
     */
    public function index(): Response
    {
        return $this->render('api_mobile/index.html.twig', [
            'controller_name' => 'ApiMobileController',
        ]);
    }

    /**
     * @Route("/api/mobile/{id}", name="api_piece", methods={"GET"})
     */
    public function getPiece($id): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $piecesRepository = $em->getRepository(Piece::class);
        $piece = $piecesRepository->find($id);
        $pieceArray = $piece->toArrayMobile();

        return new JsonResponse($pieceArray, Response::HTTP_OK);
    }

    /**
     * @Route("/api/mobile/list", name="api_piece_listeDisponible", methods={"GET"})
     */
    public function getAvailableListPieces(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $piecesRepository = $em->getRepository(Piece::class);
        $listpiece = $piecesRepository->findAll();
        $pieceArray = array();
        foreach($listpiece as $piece){
            $qqt = ($piece->getQteTotal()) - ($piece->getQteEmprunter()) - ($piece->getQteBrise()) - ($piece->getQtePerdu());
            if ($qqt > 0) {
                $pieceDesc = $piece->toArrayInventory();
                array_push($pieceArray,$pieceDesc);
            }
        }
        

        return new JsonResponse($pieceArray, Response::HTTP_OK);
    }

    /**
     * @Route("/api/mobile/empruntstate/{id}", name="api_piece_stateEmprunt", methods={"GET"})
     */
    public function getComandState($id): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $empruntRepository = $em->getRepository(Emprunt::class);
        $emprunt = $empruntRepository->find($id);
        

        return new JsonResponse($pieceArray, Response::HTTP_OK);
    }
    
}
