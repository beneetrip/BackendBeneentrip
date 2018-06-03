<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use AdminBundle\Entity\Page;
use AdminBundle\Form\Type\PageType;

class PageController extends Controller
{
	
    public function ajouterAction()
    {
		$page= new Page();
		$form = $this->createForm('adminbundle_page', $page);   
		return $this->render('AdminBundle:Page:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerPage', 'bouton'=>'Enregistrer')); 	  	
	 }

	 public function creerAction()
    {
		$page= new Page();
		$form = $this->createForm('adminbundle_page', $page); 
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
		     $this->get('session')->getFlashBag()->add('info', 'Page creee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterPage') );
	 }
	 
	 public function listeAction()
    {
    	     
		$listePages = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Page')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Page:liste.html.twig',array('listePages' => $listePages));
    }
         
    public function supprimerAction($id)
    {
    	    
		$pageId=$this->getDoctrine()->getManager()->getRepository('AdminBundle:Page')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($pageId);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Page supprimee avec Succes');
		$listePages = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Page')->myFindAll();
		return $this->render('AdminBundle:Page:liste.html.twig',array('listePages' => $listePages));
    }
    
     public function prendreAction($id)
    {
		$pageId=$this->getDoctrine()->getManager()->getRepository('AdminBundle:Page')->myFindOne($id);
		$form = $this->createForm('adminbundle_page', $pageId);   
		return $this->render('AdminBundle:Page:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierPage', 'bouton'=>'Modifier','idPage' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$page= new Categorie();
		$form = $this->createForm('adminbundle_page', $page); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$pageDB=$em->getRepository('AdminBundle:Page')->myFindOne($id);
		$form = $this->createForm('adminbundle_page', $pageDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Page modifiee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterPage') );
	 }


}