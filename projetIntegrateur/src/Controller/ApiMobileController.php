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
    25 avril    Oli     Création des méthodes pour authentifier l'utilisateur et faire la modification de son mot de passe


 ****************************************/


namespace App\Controller;

use App\Entity\Piece;
use App\Entity\Emprunt;
use App\Entity\Utilisateur;
use App\Entity\Categorie;
use App\Entity\EtatEmprunt;
use App\Entity\BDVersion;
use App\Entity\Session;
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
        
        $lst = ['lstPiece' =>$pieceArray];
        return new JsonResponse($lst, Response::HTTP_OK);
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
     * @Route("/api-mobile-annuleremprunt/{id}", name="api_piece_annulerEmprunt", methods={"GET"})
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
        
        $lst = ['lstPiece' =>$pieceArray];
        return new JsonResponse($lst, Response::HTTP_OK);
    }
    /**
     * @Route("/api-mobile-getCategories", name="api-mobile-getCategories", methods={"GET"})
     */
    public function getallCategorie(): JsonResponse
    {
        
        $categorierepository = $this->getDoctrine()->getManager()->getRepository(Categorie::class);
        $lstCategorie = $categorierepository->findAll();

        $catArrat = [];
        foreach($lstCategorie as $cat){
            //$qqt = ($piece->getQteTotal()) - ($piece->getQteEmprunter()) - ($piece->getQteBrise()) - ($piece->getQtePerdu());
           // if ($qqt > 0) {
                $catDescr = $cat->catDescription();
                array_push($catArrat,$catDescr);
                
            //}
        }
        
        $lst = ['lstcat' =>$catArrat];
        return new JsonResponse($lst, Response::HTTP_OK);
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
    /**
     * @Route("/api-mobile-checkEmpruntUser/{userId}", name="api-mobile-checkEmpruntUser", methods={"GET"})
     */
    public function empruntParUser($userId): JsonResponse{
        
        $em = $this->getDoctrine()->getManager();

        $empruntRepository = $em->getRepository(Emprunt::class);
        $lstEmprunt = $empruntRepository->lstAllReservationForUser($userId);
        
       

        /*$empruntArray = [];
        var_dump($lstEmprunt);
        die();
        //$user->getEmprunts()
        foreach($lstEmprunt as $emp){

                $state = $etatRepository->findBy($emp->getIdEtat());
            
                //var_dump($emp);
                $empruntDescription = $emp->empDescription($state->getNom());
                array_push($empruntArray,$empruntDescription);
                
        }*/
        

        $message = ["lstEmprunt"=>$lstEmprunt];
        return new JsonResponse($message, Response::HTTP_OK);
    }
    /**
     * @Route("/api-mobile-authenticate", name="api-mobile-authenticate", methods={"POST"})
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
        
        if($user == NULL){
            $response = ['id' => '0'];            
            return new JsonResponse($response, Response::HTTP_OK);
        }
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
     * @Route("/api-mobile-new-password", name="api_mobile_new_password", methods={"POST"})
     */
    public function changePasswordUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = $request->toArray();

        if(empty($data)){
            $erreur = "la requête ne possédait aucun contenu.";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }

        /*
        if (!is_bool($data['conditions'])) {
            $erreur = "la valeur 'conditions' doit être de type boolean.";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }
        */

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));

        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $data['password']
            )
        );

        $user->setPasswordReset(false);
        $user->setConditionUtilisation(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $responseMessage = ["message" => "le mot de passe a été changé avec succès !"];

        return new JsonResponse($responseMessage, Response::HTTP_OK); 
    }

    /**
     * @Route("/api-mobile-passwordStatus", name="api_mobile_passwordStatus", methods={"POST"})
     */
    public function getPasswordStatus(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));

        if ($user) {
            $response = ['changePassword' => ($user->getPasswordReset()) ? 'true' : 'false'];
            return new JsonResponse($response, Response::HTTP_OK);
        } else {
            $erreur = "aucun utilisateur n'est associé à cette adresse courriel.";
            return new JsonResponse($erreur, Response::HTTP_OK);
        }
    }

     /**
     * @Route("/api-mobile-emailUsed", name="api_mobile_emailUsed", methods={"POST"})
     */
    public function getEmailUsed(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));

        $response = "";
        if ($user) {
            $response = ["emailUsed" => "true"];
        } else {
            $response = ["emailUsed" => "false"];
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/api-mobile-verifyPassword", name="api_mobile_verifyPassword", methods={"POST"})
     */
    public function verifyPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = $request->toArray();
        $user = new Utilisateur();

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));

        $result = $passwordEncoder->isPasswordValid($user, $data['password']);

        $response = ["samePassword" => ($result) ? 'true' : 'false'];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/api-mobile-resetPassword", name="api_mobile_resetPassword", methods={"POST"})
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): JsonResponse
    {
        $data = $request->toArray();

        $em = $this->getDoctrine()->getManager();
        $utilisateurRepository = $em->getRepository(Utilisateur::class);

        $user = $utilisateurRepository->findOneBy(array('email' => $data['email']));

        $password = getToken(12);

        $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );

        $user->setPasswordReset(true);

        $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        $message = (new \Swift_Message("Réinitialisation du mot de passe - site d'emprunt du Cégep de Sherbrooke (TI)"))
                ->setFrom('pieces.de.nico@gmail.com')
                ->setTo($data['email'])
                ->setBody(
                    $this->renderView(
                        'email/reinitialiserMDP.html.twig', [
                            'password' => $password
                        ]
                        ),
                        'text/html'
                    );
        $mailer->send($message);

        $messageResponse = ["message" => "mot de passe réinitialisé avec succès !"];

        return new JsonResponse($messageResponse, Response::HTTP_OK);
    }
    /**
     * @Route("/api-mobile-reserverPieces/{idPiece}/{qqtPiece}/{idUser}/{retour}", name="api_piece_ajoutEmprunt", methods={"GET"})
     */
    public function ajoutEmprunt($idPiece,$qqtPiece,$idUser,$retour): JsonResponse
    {
        //TODO ajout check si qqtPiece<piecesDisponible
        //TODO check si user peut reserver
        
        //Modifier qqtPiece
        $emp = $this->getDoctrine()->getManager();
        $piecesRepository = $emp->getRepository(Piece::class);
        $piece = $piecesRepository->find($idPiece);
       
        $piecesRepository->updateQte($idPiece,$piece->getQteEmprunter() + $qqtPiece);


        //getSessionCourante
        $sessionDeCours = new Session();
        $emS = $this->getDoctrine()->getManager();
        $sessionRepository = $emS->getRepository(Session::class);
        
        $sessionID = $sessionRepository->getLastSession();
       
        $sessionDeCours = $sessionRepository->find($sessionID[0]['id']);
       

        $user = new Utilisateur();
        $emU = $this->getDoctrine()->getManager();
        $userRepository = $emU->getRepository(Utilisateur::class);
        $user = $userRepository->find($idUser);
        
        $etat = new EtatEmprunt();
        $emE = $this->getDoctrine()->getManager();
        $etatRepo = $emE ->getRepository(EtatEmprunt::class);
        $etat = $etatRepo->find(1);

        $today = new \DateTime();
        $dureEmprunt = $retour ." days";
        $dateRetour = new \DateTime();
        date_add($dateRetour, date_interval_create_from_date_string($dureEmprunt));
        $emprunt = new Emprunt();
        //Creation de l'emprunt a insert
        $emprunt->setQteInitiale($qqtPiece);
        $emprunt->setDateDemande($today);
        $emprunt->setDateRetourPrevue($dateRetour);
        $emprunt->setIdUtilisateur($user);
        $emprunt->setIdPiece($piece);
        $emprunt->setIdSession($sessionDeCours);
        $emprunt->setIdEtat($etat);
        $emprunt->setQteActuelle($qqtPiece);
        
        
        //Ajout emprunt
        $em = $this->getDoctrine()->getManager();
        $empruntRepository = $em->getRepository(Emprunt::class);
        $em->persist($emprunt);
        $em->flush();

        $message = ["idEmprunt" =>$emprunt->getId()];

        return new JsonResponse($message, Response::HTTP_OK);
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

    
