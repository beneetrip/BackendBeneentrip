<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Langue;
use BusinessModelBundle\Form\Type\LangueType;

class LangueController extends Controller
{
	
    public function ajouterAction()
    {
		$langue= new Langue();
		$form = $this->createForm('businessmodelbundle_langue', $langue);   
		return $this->render('AdminBundle:Langue:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerLangue', 'bouton'=>'Enregistrer')); 	  	
	 }

	 public function creerAction()
    {
		$langue= new Langue();
		$form = $this->createForm('businessmodelbundle_langue', $langue); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($langue);
		$em->flush();
		     $this->get('session')->getFlashBag()->add('info', 'Langue creee avec Succes');
		     return $this->redirect( $this->generateUrl('ajouterLangue') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterLangue') );
		return $this->render('AdminBundle:Langue:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerLangue', 'bouton'=>'Enregistrer')); 	  	
	 }
	 
	 public function listeAction()
    {
    	     
		$listeLangues = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Langue')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Langue:liste.html.twig',array('listeLangues' => $listeLangues));
    }
         
    public function supprimerAction($id)
    {
    	    
		$langueId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Langue')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($langueId);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Langue supprimee avec Succes');
		$listeLangues = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Langue')->myFindAll();
		return $this->render('AdminBundle:Langue:liste.html.twig',array('listeLangues' => $listeLangues));
    }
    
     public function prendreAction($id)
    {
		$langueId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Langue')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_langue', $langueId);   
		return $this->render('AdminBundle:Langue:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierLangue', 'bouton'=>'Modifier','idLangue' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$langue= new Categorie();
		$form = $this->createForm('businessmodelbundle_langue', $langue); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$langueDB=$em->getRepository('BusinessModelBundle:Langue')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_langue', $langueDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Langue modifiee avec Succes');
		return $this->redirect( $this->generateUrl('ajouterLangue') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterLangue') );
		return $this->render('AdminBundle:Langue:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierLangue', 'bouton'=>'Modifier','idLangue' => $id)); 	  	
	 }


}
