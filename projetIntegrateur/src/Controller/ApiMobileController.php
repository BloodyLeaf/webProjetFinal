<?php
/****************************************
   Fichier : ApiMobileController.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : web13 ,web14, web16, web17 
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
25 avril 2021    Approuvé par l'équipe
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
    19 avril 	P-À		Premier Test pour l’api Création de la route 					/api-mobile/{id} et de la route /api-mobile-list
    20 avril 	P-À		Tente de résoudre un probleme lié au route ( m'a malheureusement pris ma journée 
    21 Avril	P-À		Résolution du probleme de route et ajout de la fonction supprimer qui ne fonctionne pas entiermeent
    22 avril 	P-À		Tente de résoudre le probleme de la fonction supprimer et ajout de d’une fonction pour envoyer l'entièreté de l’inventaire et les états des commandes. Malheuresement j’ai des bug et elle ne sont pas fonctionnel
    23 Avril 	P-À		Tente de regler les bug associer à la suppresion d’une commande, l’envoi de l’inventaire et l’Affichage des états. Pas reglé masi le probleme viens de doctrine lorsqu’il veut envoyer des clé étran
    24 avril	P-À		Probleme de doctrine reglé fichier API complété


 ****************************************/


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

    
