<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{

   /*
    public function listUtilisateurs() {
        $em = $this->getDoctrine()->getManager();
        $utilisateursRepository = $em->getRepository(Utilisateur::class);

        $listeUtilisateurs = $utilisateursRepository->findAll();
        return $this->render('utilisateur/listProduits.html.twig', ['listeproduits' => $listeProduits]);
    }
    */
    /**
     * @Route("/ajoututilisateur", name="ajouter_utilisateur")
     */
    public function ajouterUtilisateur (Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurFormType::class, $user);

        $form->add('ajouter', SubmitType::class, array('label'=>'Ajouter'));
        $form->handleRequest($request);

        if ($request->isMethod('post') && $form->isValid()) {
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
                        'email/inscription.html.twig', [
                            'form' => compact('infoInscription'),
                            'password' => $password
                        ]
                        ),
                        'text/html'
                    );
            $mailer->send($message);

            return $this->redirect($this->generateUrl('ajouter_utilisateur'));
        }

        return $this->render('utilisateur/utilisateurForm.html.twig', [
            'utilisateurForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifierutilisateur/{id}", name="modifier_utilisateur")
     */
    public function modifierUtilisateur(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $UtilisateurRepository = $em->getRepository(Utilisateur::class);

        $utilisateur = $UtilisateurRepository->find($id);
        $form = $this->createForm(UtilisateurFormType::class, $utilisateur);
        $form->add('sauvegarder', SubmitType::class, ['label' => 'sauvegarder']);

        $form->handleRequest($request);

        if($request->isMethod('post') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirect($this->generateUrl('ajouter_utilisateur'));
        }
        return $this->render('utilisateur/utilisateurForm.html.twig', ['utilisateurForm' => $form->createView()]);
    }
}
