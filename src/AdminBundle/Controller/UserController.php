<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use AdminBundle\Entity\User;
use AdminBundle\Form\Type\UserType;

class UserController extends Controller
{
	
    public function ajouterAction()
    {
		$user= new User();
		$form = $this->createForm('adminbundle_user', $user);   
		return $this->render('AdminBundle:User:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerUtilisateur', 'bouton'=>'Enregistrer')); 	  	
	 }

	 public function creerAction()
    {
		$user= new User();
		$form = $this->createForm('adminbundle_user', $user); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();
		     $this->get('session')->getFlashBag()->add('info', 'Utilisateur cree avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterUtilisateur') );
	 }
	 
	 public function listeAction()
    {
    	     
		$listeUsers = $this->getDoctrine()->getManager()->getRepository('AdminBundle:User')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:User:liste.html.twig',array('listeUsers' => $listeUsers));
    }
         
    public function supprimerAction($id)
    {
    	    
		$userId=$this->getDoctrine()->getManager()->getRepository('AdminBundle:User')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($userId);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Utilisateur supprimee avec Succes');
		$listeUsers = $this->getDoctrine()->getManager()->getRepository('AdminBundle:User')->myFindAll();
		return $this->render('AdminBundle:User:liste.html.twig',array('listeUsers' => $listeUsers));
    }
    
     public function prendreAction($id)
    {
		$userId=$this->getDoctrine()->getManager()->getRepository('AdminBundle:User')->myFindOne($id);
		$form = $this->createForm('adminbundle_user', $userId);   
		return $this->render('AdminBundle:User:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierUtilisateur', 'bouton'=>'Modifier','idUser' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$user= new User();
		$form = $this->createForm('adminbundle_user', $user); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$userDB=$em->getRepository('AdminBundle:User')->myFindOne($id);
		$form = $this->createForm('adminbundle_user', $userDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Utilisateur modifiee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterUtilisateur') );
	 }


}