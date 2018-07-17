<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Reservation;
use BusinessModelBundle\Form\Type\ReservationType;

class ReservationController extends Controller
{
	
    public function ajouterAction()
    {
		$reservation= new Reservation();
		$form = $this->createForm('businessmodelbundle_reservation', $reservation);   
		return $this->render('AdminBundle:Reservation:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerReservation', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
	 }

	 public function creerAction()
    {
		$reservation= new Reservation();
		$form = $this->createForm('businessmodelbundle_reservation', $reservation); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($reservation);
		$em->flush();
		     $elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		     $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.EnregistrerMessage',array('%elt%' => $elt)));
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterReservation') );
	 }
	 
	 public function listeAction()
    {
    	     
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Reservation:liste.html.twig',array('listeReservations' => $listeReservations));
    }
         
    public function supprimerAction($id)
    {
    	    
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($reservationId);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.SupprimerMessage',array('%elt%' => $elt)));
		$listePages = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindAll();
		return $this->render('AdminBundle:Reservation:liste.html.twig',array('listeReservations' => $listeReservations));
    }
    
     public function prendreAction($id)
    {
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_reservation', $reservationId);   
		return $this->render('AdminBundle:Reservation:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierReservation', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'idReservation' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$reservation= new Reservation();
		$form = $this->createForm('businessmodelbundle_reservation', $reservation); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$reservationDB=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_reservation', $reservationDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.ModifierMessage',array('%elt%' => $elt)));
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterReservation') );
	 }
	 
	 //Fonction speciale permettant de voir l'activite d'une reservation
    public function voirActiviteAction($id)
    {
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$listeActivites=[];
		$listeActivites[] = $reservationId->getActivite();
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }


}
