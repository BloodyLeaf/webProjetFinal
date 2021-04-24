<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Entity\Emprunt;
use App\Entity\Utilisateur;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Security\AuthentificateurAuthenticator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $empruntStateArray = $emprunt->jetatEmprunt();

        return new JsonResponse($empruntStateArray, Response::HTTP_OK);
    }

    /**
     * @Route("/api-mobile-annulerempruntstate/{id}", name="api_piece_annulerEmprunt", methods={"GET"})
     */
    public function annulerEmprunt($id): JsonResponse
    {
        //j'ai besoin d'un premier utilisateur mais je vais attendre que l'interface d'inscription sois fait pour éviter des probleme 
        // d'un mauvais insert 
        // REEEEEEEEEEEEEEE
        $em = $this->getDoctrine()->getManager();
        $empruntRepository = $em->getRepository(Emprunt::class);
        $emprunt = $empruntRepository->find($id);

        $emp = $this->getDoctrine()->getManager();
        $piecesRepository = $em->getRepository(Piece::class);
        $piece = $piecesRepository->find($emprunt->getIdPiece());

        $message = "";
        if($emprunt->getIdEtat() == 1){
            $empruntRepository->updateEtat($id,4);
            $piecesRepository($piece->getId(),$piece->getQteEmprunter() - $emprunt->getQteActuelle());
            $message = "Emprunt annule avec succes";
        }
        else{
            $message = "Impossible d'annuler la reservation";
        }

        return new JsonResponse($message, Response::HTTP_OK);
    }

    /**
     * @Route("/api-mobile-authenticate", name="api_piece_stateEmprunt", methods={"GET"})
     */
    public function authenticateUtilisateur(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = $request->toArray();

        $erreur = "";
        if(empty($data)){
            $erreur = "la requête ne possédait aucun contenu";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }
    
        /*
        if(!Validator::isValidJsonString($data)){
            $erreur = "le contenu de la requête ne correspond au type JSON";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }*/

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));

        $passwordEntered = sha1($data['password']);

        if($user->getPassword() == $passwordEntered) {
            return new JsonResponse($user->getId(), Response::HTTP_OK);
        } 

        $result = "erreur";

        return new JsonResponse($passwordEntered, Response::HTTP_OK);
    }


    
}
