<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Categorie;
use BusinessModelBundle\Form\Type\CategorieType;

class CategorieController extends Controller
{
	
    public function ajouterAction()
    {
		$categorie= new Categorie();
		$form = $this->createForm('businessmodelbundle_categorie', $categorie);   
		return $this->render('AdminBundle:Categorie:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerCategorie', 'bouton'=>'Enregistrer')); 	  	
	 }

	 public function creerAction()
    {
		$categorie= new Categorie();
		$form = $this->createForm('businessmodelbundle_categorie', $categorie); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($categorie);
		$em->flush();
		     $this->get('session')->getFlashBag()->add('info', 'Categorie creee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterCategorie') );
	 }
	 
	 public function listeAction()
    {
    	     
		$listeCategories = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Categorie')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Categorie:liste.html.twig',array('listeCategories' => $listeCategories));
    }
         
    public function supprimerAction($id)
    {
    	    
		$categorieId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Categorie')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($categorieId);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Categorie supprimee avec Succes');
		$listeCategories = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Categorie')->myFindAll();
		return $this->render('AdminBundle:Categorie:liste.html.twig',array('listeCategories' => $listeCategories));
    }
    
     public function prendreAction($id)
    {
		$categorieId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Categorie')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_categorie', $categorieId);   
		return $this->render('AdminBundle:Categorie:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierCategorie', 'bouton'=>'Modifier','idCategorie' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$categorie= new Categorie();
		$form = $this->createForm('businessmodelbundle_categorie', $categorie); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$categorieDB=$em->getRepository('BusinessModelBundle:Categorie')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_categorie', $categorieDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Categorie modifiee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterCategorie') );
	 }


}
