<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoInscription = $form->getData();
            $email = $form->get('email')->getData();
            $password = getToken(12);
            $user->setEmail($email);
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setRoles($form->get('roles')->getData());
            $user->setEtat(false);
            $user->setPasswordReset(true);
            $user->setConditionUtilisation(false);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message("Confirmation d'inscription - site d'emprunt du Cégep de Sherbrooke (TI)"))
                ->setFrom('pieces.de.nico@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/inscription.html.twig', [
                            'form' => compact('infoInscription'),
                            'password' => $password
                        ]
                        ),
                        'text/html'
                    )
            ;

            $mailer->send($message);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
