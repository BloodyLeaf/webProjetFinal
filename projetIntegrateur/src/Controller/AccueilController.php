<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\EtatEmprunt;
use App\Entity\IncidentEmprunt;
use App\Entity\Piece;
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
    public function changeEtat(Request $r): Response
    {
        $info = $r->request->all();
        
      
       $em = $this->getDoctrine()->getManager();
       $em->getRepository(Emprunt::class)->updateEtat($info['id'],$info['etat']);
       $em->flush();
        
       return new Response();
    }


    /**
     * @Route("/retourPiece", name="retourPiece")
     */
    public function returnLoan(Request $r): Response
    {
        $info = $r->request->all();
        $response = new Response();

        //regex qte retourner
        if(!preg_match('/^0*[0-9]\d*$/',$info['QTERetourner'])){
            $response->setContent("fail");
            return $response;
        }

        $em = $this->getDoctrine()->getManager();
        //trouve l'emprunt en question avec l'id
        $emprunt = $em->getRepository(Emprunt::class)->findOneBy(['id' => $info['id']]);
        //Chanque la QTE de l'emprunt et garde la nouvelle QTE
        $emprunt->setQteActuelle($emprunt->getQteActuelle() - $info['QTERetourner']);
        //get le Id de la pièce
        $pieceID = $emprunt->getIdPiece();
        //get la piece
        $piece =  $em->getRepository(Piece::class)->findOneBy(['id' => $pieceID]);

        //si QTE est à zéro, emprunt terminer
        if($emprunt->getQteActuelle() == 0){
            $em->getRepository(Emprunt::class)->updateEtat($info['id'],4);
        }       

        //diminue les QTE
        if(sizeof($info) > 2){

            //regex qte brise
            if(!preg_match('/^0*[0-9]\d*$/',$info['QTEBrise'])){
                $response->setContent("fail");
                return $response;
            }
            
            //regex qte perdu
            if(!preg_match('/^0*[0-9]\d*$/',$info['QTEPerdu'])){
                $response->setContent("fail");
                return $response;
            }

            
            if($info['QTEBrise'] != 0){
                $piece->setQteBrise($piece->getQteBrise() + $info['QTEBrise']);
            }
                
        
            if($info['QTEPerdu'] != 0){
                $piece->setQtePerdu($piece->getQtePerdu() + $info['QTEPerdu']);
            }
            
            $QTETotal = $info['QTEPerdu'] + $info['QTEBrise'];
            $piece->setQteTotal($piece->getQteTotal() - $QTETotal);

            $incident = new IncidentEmprunt();
            $incident->setIdEmprunt($emprunt);
            $incident->setQte($QTETotal);
            $incident->setDescription($info['desc']);

            $em->persist($incident);
        }

        $piece->setQteEmprunter($piece->getQteEmprunter() - $info['QTERetourner']);

        
        $em->flush();

        return $response;
    }


}
