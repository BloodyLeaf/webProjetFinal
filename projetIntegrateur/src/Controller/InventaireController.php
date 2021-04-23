<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Piece;
use App\Form\AddPieceType;
use App\Form\ModifyPieceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


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
    
            return $this->redirect($this->generateUrl('inventaire'));
        }
        return $this->render('inventaire/addPiece.html.twig', [
            'addPieceForm' => $form->createView(),
        ]);

    }
            /**
     * @Route("/modify", name="modify_piece")
     */
    public function modifyPiece(Request $request): Response
    {
        $piece = new Piece();
        $form = $this->createForm(ModifyPieceType::class, $piece);

        $form->add('ajouter', submitType::class, array('label'=>'Ajouter'));
        $form->handleRequest($request);

        if( $request->isMethod('post') && $form->isValid())
        {
            $default = 0;
            $infoPiece = $form->getData();

            $piece->setNom($form->get('nom')->getData());
            $piece->setDescription($form->get('description')->getData());
            $piece->setQteTotal($form->get('QteTotal')->getData());
            $piece->setQteEmprunter($form->get('QteEmprunter')->getData());
            $piece->setQteBrise($form->get('QteBrise')->getData());
            $piece->setQtePerdu($form->get('QtePerdu')->getData());
            $piece->setIdCategorie($form->get('idCategorie')->getData());



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($piece);
            $entityManager->flush();
    
            return $this->redirect($this->generateUrl('inventaire'));
        }
        return $this->render('inventaire/ModifyPiece.html.twig', [
            'modifyPieceForm' => $form->createView(),
        ]);

    }
}
