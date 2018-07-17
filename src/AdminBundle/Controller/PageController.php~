<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Page;
use BusinessModelBundle\Form\Type\PageType;

class PageController extends Controller
{
	
    public function ajouterAction()
    {
		$page= new Page();
		$form = $this->createForm('businessmodelbundle_page', $page);   
		return $this->render('AdminBundle:Page:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerPage', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
	 }

	 public function creerAction()
    {
		$page= new Page();
		$form = $this->createForm('businessmodelbundle_page', $page); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($page);
		$em->flush();
		           $elt=$this->get('translator')->trans('Barre.Page.Mot');
		     $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.EnregistrerMessage',array('%elt%' => $elt)));
		     return $this->redirect( $this->generateUrl('ajouterPage') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterPage') );
	   return $this->render('AdminBundle:Page:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerPage', 'bouton'=>
	   $this->get('translator')->trans('Action.Enregistrer'))); 	  	
	 }
	 
	 public function listeAction()
    {
    	     
		$listePages = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Page')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Page:liste.html.twig',array('listePages' => $listePages));
    }
         
    public function supprimerAction($id)
    {
    	    
		$pageId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Page')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($pageId);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Page.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.SupprimerMessage',array('%elt%' => $elt)));
		$listePages = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Page')->myFindAll();
		return $this->render('AdminBundle:Page:liste.html.twig',array('listePages' => $listePages));
    }
    
     public function prendreAction($id)
    {
		$pageId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Page')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_page', $pageId);   
		return $this->render('AdminBundle:Page:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierPage', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'idPage' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$page= new Page();
		$form = $this->createForm('businessmodelbundle_page', $page); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$pageDB=$em->getRepository('BusinessModelBundle:Page')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_page', $pageDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Page.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.ModifierMessage',array('%elt%' => $elt)));
		return $this->redirect( $this->generateUrl('ajouterPage') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterPage') );
		return $this->render('AdminBundle:Page:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierPage', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'idPage' => $id)); 	  	
	 }


}
