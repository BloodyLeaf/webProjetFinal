<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\EtatEmprunt;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {   

        $reservationrepository = $this->getDoctrine()->getManager()->getRepository(Emprunt::class);
        $lstReservation = $reservationrepository->lstReservation();

        $etatEmpruntrepository = $this->getDoctrine()->getManager()->getRepository(EtatEmprunt::class);
        $lstEtat = $etatEmpruntrepository->findAll();
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'reservations' => $lstReservation,
            'etats' => $lstEtat,
        ]);
        
    }


     /**
     * @Route("/changeEtat", name="changeEtat")
     */
    public function changeQTETotal(Request $r): Response
    {
        $info = $r->request->all();
        
        $em = $this->getDoctrine()->getManager();
        $em->getRepository(Emprunt::class)->updateEtat($info['id'],$info['etat']);
        $em->flush();
        
        return new Response();
    }

}
