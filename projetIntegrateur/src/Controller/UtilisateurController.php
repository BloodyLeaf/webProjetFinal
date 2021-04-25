<?php
/****************************************
   Fichier : UtilisateurController.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
21 avril 2021 / Olivier / Ajouter fonction listUtilisateurs qui permet d’afficher l’ensemble des utilisateurs du site
21 avril 2021 / Olivier / Ajouter fonction ajouterUtilisateur qui permet d’afficher et de traiter un formulaire permettant l’ajout d’un utilisateur au site
21 avril 2021 / Olivier / Ajouter fonction modifierUtilisateur qui permet d’afficher et de traiter un formulaire permettant la modification d’un utilisateur du site
 ****************************************/

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

    /**
     * @Route("/utilisateurs", name="liste_utilisateurs")
     */
    public function listUtilisateurs() {
        $em = $this->getDoctrine()->getManager();
        $utilisateursRepository = $em->getRepository(Utilisateur::class);

        $listeUtilisateurs = $utilisateursRepository->findAll();
        return $this->render('utilisateur/listUtilisateurs.html.twig', ['utilisateurs' => $listeUtilisateurs]);
    }
    
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
            $user->setNoGroupe(0);
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

            $session = $request->getSession();
            $session->getFlashBag()->add('ajout', 'utilisateur ajouté avec succès!');

            return $this->redirect($this->generateUrl('liste_utilisateurs'));
        }

        /*return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);*/

        return $this->render('utilisateur/utilisateurForm.html.twig', [
            'utilisateurForm' => $form->createView(),
            'title' => 'Ajouter un utilisateur',
            'type' => 'ajout',
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
        $form->add('sauvegarder', SubmitType::class, array('label'=>'sauvegarder'));

        $form->handleRequest($request);

        if($request->isMethod('post') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('modification', 'utilisateur modifié avec succès!');

            return $this->redirect($this->generateUrl('liste_utilisateurs'));
        }
        return $this->render('utilisateur/utilisateurForm.html.twig', [
            'utilisateurForm' => $form->createView(),
            'title' => 'Modifier un utilisateur',
            'type' => 'modifier',
            ]);
    }
}
