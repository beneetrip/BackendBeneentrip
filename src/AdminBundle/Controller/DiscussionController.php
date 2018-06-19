<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Discussion;
use BusinessModelBundle\Form\Type\DiscussionType;

class DiscussionController extends Controller
{
	
    public function ajouterAction()
    {
		$discussion= new Discussion();
		$form = $this->createForm('businessmodelbundle_discussion', $discussion);   
		return $this->render('AdminBundle:Discussion:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerDiscussion', 'bouton'=>'Enregistrer')); 	  	
	 }

	 public function creerAction()
    {
		$discussion= new Discussion();
		$form = $this->createForm('businessmodelbundle_discussion', $discussion);   
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($discussion);
		$em->flush();
		     $this->get('session')->getFlashBag()->add('info', 'Discussion creee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterDiscussion') );
	 }
	 
	public function listeAction()
    {
    	     
		$listeDiscussions = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Discussion:liste.html.twig',array('listeDiscussions' => $listeDiscussions));
    }
         
    public function supprimerAction($id)
    {
    	    
		$discussionId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($discussionId);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Discussion supprimee avec Succes');
		$listeDiscussions = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindAll();
		return $this->render('AdminBundle:Discussion:liste.html.twig',array('listeDiscussions' => $listeDiscussions));
    }
    
    public function prendreAction($id)
    {
		$discussionId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_discussion', $discussionId);   
		return $this->render('AdminBundle:Discussion:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierDiscussion', 'bouton'=>'Modifier','idDiscussion' => $id)); 	  	
	}
	 
	 
	public function modifierAction($id)
    {
		$discussion= new Discussion();
		$form = $this->createForm('businessmodelbundle_discussion', $discussion);   
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$discussionDB=$em->getRepository('BusinessModelBundle:Discussion')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_discussion', $discussionDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Discussion modifiee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterDiscussion') );
	 }


}