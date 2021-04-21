<?php

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
                return $this->redirectToRoute('app_register');
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

        $form->add('ajouter', SubmitType::class, ['label' => 'confirmer']);

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

            return $this->redirect($this->generateUrl('app_register'));
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
