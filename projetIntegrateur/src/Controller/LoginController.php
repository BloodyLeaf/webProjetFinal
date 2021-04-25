<?php
/****************************************
   Fichier : LoginController.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   25 avril 2021    Approuvé par l'équipe
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
    20 avril 2021 / Olivier / Ajouter fonction Login qui affiche le form de login, récupère les erreurs liés à l’authentification et les affiche et finalement redirige l’utilisateur si l’authentification est complété ou bien que l’utilisateur est déjà connecté
    20 avril 2021 / Olivier / Ajouter fonction changePassword qui permet de récupérer le nouveau mot de passe entré par l’utilisateur après sa première connexion au site
    20 avril 2021 / Olivier / ajouter fonction logout associé à la route /logout permettant la déconnexion de l’utilisateur
 ****************************************/

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ChangePasswordFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
            $user = $this->getUser();
            if ($user->getPasswordReset() == true)
                return $this->redirectToRoute('change_password');
            else
                return $this->redirectToRoute('accueil');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/password", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Utilisateur;
        $user = $this->getUser();

        if ($user->getPasswordReset() == false) {
            return $this->redirect($this->generateUrl('app_login'));
        }

        $form = $this->createForm(ChangePasswordFormType::class);

        $form->add('confirmer', SubmitType::class, ['label' => 'confirmer']);

        $form->handleRequest($request);

        if($request->isMethod('post') && $form->isValid()) {

            $password = $form->get('plainPassword')->getData();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );
            $user->setPasswordReset(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('security/changePassword.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
