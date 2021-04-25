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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiMobileController extends AbstractController implements UserInterface
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
     * @Route("/api-mobile-authenticate", name="api_piece_stateEmprunt", methods={"POST"})
     */
    public function authenticateUtilisateur(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = $request->toArray();

        if(empty($data)){
            $erreur = "la requête ne possédait aucun contenu";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));
        
        $result = $passwordEncoder->isPasswordValid($user, $data['password']);

        if($result){
            $response = ['id' => $user->getId(), 'changePassword' => $user->getPasswordReset()];
            return new JsonResponse($response, Response::HTTP_OK);
        }  
        else{
            $response = ['id' => '0'];            
            return new JsonResponse($response, Response::HTTP_OK);
        }  
    }

    /**
     * @Route("/api-mobile-new-password", name="api_piece_stateEmprunt", methods={"POST"})
     */
    public function changePasswordUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = $request->toArray();

        if(empty($data)){
            $erreur = "la requête ne possédait aucun contenu.";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }

        if (!is_bool($data['conditions'])) {
            $erreur = "la valeur 'conditions' doit être de type boolean.";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));

        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $data['password']
            )
        );

        $user->setConditionUtilisation($data['conditions']);

        return new JsonResponse('le mot de passe a été changé avec succès !', Response::HTTP_OK); 
    }

    public function getRoles(){


        return [];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword(){

        return null;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(){
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(){
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
        return [];
    }
    
}
