<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Piece;
use App\Entity\Emprunt;
use App\Entity\Categorie;
use App\Entity\EtatEmprunt;
use App\Entity\BDVersion;

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
     * @Route("/api-mobile/{id}", name="api_piece", methods={"GET"})
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
     * @Route("/api-mobile-list", name="api_piece_listeDisponible", methods={"GET"})
     */
    public function getAvailableListPieces(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $piecesRepository = $em->getRepository(Piece::class);
        $listpiece = $piecesRepository->findAll();
        $pieceArray = array();
        foreach($listpiece as $piece){
            //$qqt = ($piece->getQteTotal()) - ($piece->getQteEmprunter()) - ($piece->getQteBrise()) - ($piece->getQtePerdu());
           // if ($qqt > 0) {
                $pieceDesc = $piece->toArrayInventory();
                array_push($pieceArray,$pieceDesc);
            //}
        }
        

        return new JsonResponse($pieceArray, Response::HTTP_OK);
    }

    /**
     * @Route("/api-mobile-empruntstate/{id}", name="api_piece_stateEmprunt", methods={"GET"})
     */
    public function getComandState($id): JsonResponse
    {
        //j'ai besoin d'un premier utilisateur mais je vais attendre que l'interface d'inscription sois fait pour éviter des probleme 
        // d'un mauvais insert 
        $em = $this->getDoctrine()->getManager();
        $empruntRepository = $em->getRepository(Emprunt::class);
        $emprunt = $empruntRepository->find($id);

        $emState = $this->getDoctrine()->getManager();
        $stateRepository = $emState->getRepository(EtatEmprunt::class);
        $state = $stateRepository->find($emprunt->getIdEtat());


        $empruntStateArray = $emprunt->jetatEmprunt($state->getId(),$state->getNom());

        return new JsonResponse($empruntStateArray, Response::HTTP_OK);
    }

    /**
     * @Route("/api-mobile-annulerempruntstate/{id}", name="api_piece_annulerEmprunt", methods={"GET"})
     */
    public function annulerEmprunt($id): JsonResponse
    { 
        // 0 annulation réussi
        // 1 echec 
        $em = $this->getDoctrine()->getManager();
        $empruntRepository = $em->getRepository(Emprunt::class);
        $emprunt = $empruntRepository->find($id);

        $emp = $this->getDoctrine()->getManager();
        $piecesRepository = $em->getRepository(Piece::class);
        $piece = $piecesRepository->find($emprunt->getIdPiece());

        $emState = $this->getDoctrine()->getManager();
        $stateRepository = $emState->getRepository(EtatEmprunt::class);
        $state = $stateRepository->find($emprunt->getIdEtat());

        $message = [];
        if($state->getId() < '3'){
            
            $empruntRepository->updateEtat($id,4);
            $piecesRepository->updateQte($piece->getId(),$piece->getQteEmprunter() - $emprunt->getQteActuelle());
            $message = ['codeErreur' => '0'];
        }
        else{
           
            $message = ['codeErreur' => '1'];
        }
        var_dump($message);
        return new JsonResponse($message, Response::HTTP_OK);
    }
    /**
     * @Route("/api-mobile-listeComplete", name="api_piece_InvetaireComplet", methods={"GET"})
     */
    public function getWholeInventory(): JsonResponse
    {
        
        $em = $this->getDoctrine()->getManager();
        $piecesRepository = $em->getRepository(Piece::class);
        $listpiece = $piecesRepository->findAll();
        $pieceArray = array();

        foreach($listpiece as $piece){
            $emCat = $this->getDoctrine()->getManager();
            $categorieRepository = $em->getRepository(Categorie::class);
            $cat = $categorieRepository->find($piece->getIdCategorie());

            $qqt = ($piece->getQteTotal()) - ($piece->getQteEmprunter()) - ($piece->getQteBrise()) - ($piece->getQtePerdu());
            if ($qqt > 0) {
                $pieceDesc = $piece->fullPiece($cat->getId(),$cat->getNom());
                array_push($pieceArray,$pieceDesc);
            }
        }
        
        return new JsonResponse($pieceArray, Response::HTTP_OK);
    }
    /**
     * @Route("/api-mobile-checkBDVersion/{version}", name="api_piece_checkBDVersion", methods={"GET"})
     */
    public function checkBDVersion($version): JsonResponse
    {
        
        $em = $this->getDoctrine()->getManager();
        $BDversionRepository = $em->getRepository(BDVersion::class);
        $BDVersion = $BDversionRepository->getLatestBDVersion();
        
        $message = [];
        if($BDVersion[0]['id'] == $version){
            $message = ['idVersion' => '0'];
        }
        else{
            $message = ["idVersion" => $BDVersion[0]['id']];
        }

        
        return new JsonResponse($message, Response::HTTP_OK);
    }
    
}

    
