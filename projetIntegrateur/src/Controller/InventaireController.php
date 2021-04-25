<?php

/****************************************
   Fichier : InventaireController.php
   Auteur : Samuel Fournier, William Goupil
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   25 avril 2021    / Olivier           Approuvé
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
    19 avril 2021	/ Samuel / Modification de la fonction index pour allez chercher la liste des pièces et catégorie
    19 avril 2021	/ Samuel / Ajout de la fonction changeQTETotal dans le controlleur pour modifier les quantités avec AJAX
    22 avril 2021	/ William / Ajout de la fonction addPiece dans le controlleur pour pouvoir ajouter une pièce dans la base de données
    22 avril 2021	/ William / Ajout de la fonction modifyPiece dans le controlleur pour pouvoir modifier une pièce dans la base de données
    23 avril 2021	/ William / debug de la fonction modifyPiece dans le controlleur
    23 avril 2021	/ William / Ajout de la fonction deletePiece dans le controlleur pour pouvoir supprimer une pièce dans la base de données
    23 avril 2021	/ William / Modification de la fonction modifyPiece dans le controlleur afin de passer les informations au form
    24 avril 2021	/ William / Correction de la fonction modifyPiece dans le controlleur
    24 avril 2021	/ William / Ajout du changement de version dans la fonction modifyPiece dans le controlleur
    24 avril 2021	/ William / Ajout du changement de version dans la fonction addPiece dans le controlleur
    24 avril 2021	/ William / Ajout de la fonction addCategorie dans le controlleur pour pouvoir supprimer une pièce dans la base de données

 ****************************************/


namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Piece;
use App\Form\AddPieceType;
use App\Form\ModifyPieceType;
use App\Form\CategorieFormType;
use App\Entity\BDVersion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\DateTime;
class InventaireController extends AbstractController
{
    /**
     * @Route("/inventaire", name="inventaire")
     */
    public function index()
    {

        $piecesrepository = $this->getDoctrine()->getManager()->getRepository(Piece::class);
        $lstPieces = $piecesrepository->lstPieceCategorie();

        $categorierepository = $this->getDoctrine()->getManager()->getRepository(Categorie::class);
        $lstCategorie = $categorierepository->findAll();

        return $this->render('inventaire/index.html.twig', [
            'controller_name' => 'InventaireController',
            'pieces' => $lstPieces,
            'categories' => $lstCategorie,
        ]);
    }

     /**
     * @Route("/chanqueQTETotal", name="chanqueQTETotal")
     */
    public function changeQTETotal(Request $r): Response
    {
        $info = $r->request->all();
        $response = new Response();

        if(!preg_match('/^0*[0-9]\d*$/',$info['qte'])){
            $response->setContent("fail");
            return $response;
        }


        $em = $this->getDoctrine()->getManager();
        $piece = $em->getRepository(piece::class)->findOneBy(['id' => $info['id']]);
        $piece->setQteTotal($info['qte']);
        $em->flush();
        return $response;
    }
        /**
     * @Route("/add", name="add_piece")
     */
    public function addPiece(Request $request): Response
    {
        $piece = new Piece();
        $form = $this->createForm(AddPieceType::class, $piece);

        $form->add('ajouter', submitType::class, array('label'=>'Ajouter'));
        $form->handleRequest($request);

        if( $request->isMethod('post') && $form->isValid())
        {
            $default = 0;
            $infoPiece = $form->getData();

            $piece->setNom($form->get('nom')->getData());
            $piece->setDescription($form->get('description')->getData());
            $piece->setQteTotal($form->get('QteTotal')->getData());
            $piece->setIdCategorie($form->get('idCategorie')->getData());

            $piece->setQteEmprunter($default);
            $piece->setQteBrise($default);
            $piece->setQtePerdu($default);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($piece);
            $entityManager->flush();
    

            $bdVersion = new Bdversion;
            $bdVersion->setTimestamp(new \DateTime());
            $bdVersion->setTableModifier("piece");
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bdVersion);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('inventaire'));
        }
        return $this->render('inventaire/addPiece.html.twig', [
            'addPieceForm' => $form->createView(),
        ]);

    }
    /**
     * @Route("/modify/{idpiece}",
     *    name="modify_piece")
     */
    public function modifyPiece(Request $request, $idpiece): Response
    {
        $em = $this->getDoctrine()->getManager();
        $PieceRepository = $em->getRepository(Piece::class);

        $ModifyPiece = $PieceRepository->find($idpiece);
        $form = $this->createForm(ModifyPieceType::class, $ModifyPiece);
        $form->add('sauvegarder', SubmitType::class, array('label'=>'sauvegarder'));
 
        $form->handleRequest($request);


        if($request->isMethod('post') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ModifyPiece);
            $em->flush();

            $bdVersion = new Bdversion;
            $bdVersion->setTimestamp(new \DateTime());
            $bdVersion->setTableModifier("piece");
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bdVersion);
            $entityManager->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add('modification', 'Piece modifié avec succès!');

            return $this->redirect($this->generateUrl('inventaire'));
        }
        return $this->render('inventaire/ModifyPiece.html.twig', [
            'modifyPieceForm' => $form->createView(),
            'title' => 'Modifier une piece',
            'type' => 'modifier',
            ]);

    }
            /**
     * @Route("/delete/{idpiece}",
     *    defaults={"idpiece" = 0},
     *    name="delete_piece")
     */ 
    public function deletePiece(Request $request, $idpiece): Response
    {
        //TODO: trouver comment identifier le produit à partir du bouton
        $piece = new Piece();
        $pieceRepo = $this->getDoctrine()->getRepository(Piece::class);
        $piece = $pieceRepo->findOneById($idpiece);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($piece);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('inventaire'));

    }
    /**
     * @Route("/addcategorie", name="add_categorie")
     */ 
    public function addCategorie(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieFormType::class, $categorie);

        $form->add('ajouter', submitType::class, array('label'=>'Ajouter'));
        $form->handleRequest($request);

        if( $request->isMethod('post') && $form->isValid())
        {
            $default = 0;
            $infoCategorie = $form->getData();

            $categorie->setNom($form->get('nom')->getData());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();
    

            $bdVersion = new Bdversion;
            $bdVersion->setTimestamp(new \DateTime());
            $bdVersion->setTableModifier("categorie");
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bdVersion);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('inventaire'));
        }
        return $this->render('inventaire/addCategorie.html.twig', [
            'CategorieForm' => $form->createView(),
        ]);
    }
}