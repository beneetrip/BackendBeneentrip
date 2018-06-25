<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\User;
use BusinessModelBundle\Form\Type\UserType;
use Symfony\Component\Form\FormError;

class UserController extends Controller
{
	
    public function ajouterAction()
    {
		$user= new User();
		$form = $this->createForm('businessmodelbundle_user', $user);   
		return $this->render('AdminBundle:User:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerUtilisateur', 'bouton'=>'Enregistrer')); 	  	
	 }

	 public function creerAction()
    {
		$user= new User();
		$form = $this->createForm('businessmodelbundle_user', $user); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParameters($request,$form,0)) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		//On ajoute le privilege parmi les roles des utilisateurs FOSuserBundle
		$user->addRole($user->getPrivilege());
		$em->persist($user);
		$em->flush();
		     $this->get('session')->getFlashBag()->add('info', 'Utilisateur cree avec Succes');
		     return $this->redirect( $this->generateUrl('ajouterUtilisateur') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterUtilisateur') );
		return $this->render('AdminBundle:User:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerUtilisateur', 'bouton'=>'Enregistrer')); 	  	
	 }
	 
	 public function listeAction()
    {
    	     
		$listeUsers = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:User:liste.html.twig',array('listeUsers' => $listeUsers));
    }
         
    public function supprimerAction($id)
    {
    	    
		$userId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($userId);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Utilisateur supprimee avec Succes');
		$listeUsers = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindAll();
		return $this->render('AdminBundle:User:liste.html.twig',array('listeUsers' => $listeUsers));
    }
    
     public function prendreAction($id)
    {
		$userId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_user', $userId);   
		return $this->render('AdminBundle:User:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierUtilisateur', 'bouton'=>'Modifier','user' => $userId)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$user= new User();
		$form = $this->createForm('businessmodelbundle_user', $user);
		$userId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne($id); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParameters($request,$form,$id)) {
		$em = $this->getDoctrine()->getManager();
		$userDB=$em->getRepository('BusinessModelBundle:User')->myFindOne($id);
		$privilege=$userDB->getPrivilege();
		$form = $this->createForm('businessmodelbundle_user', $userDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		//Si l'utilisateur a mis une photo on lance les operations de upload
		if($userDB->getFichierPhoto()!=null){
		$userDB->preUpload();
		$userDB->upload();
		}
		//on supprime l'ancien privilege et on ajoute le nouveau parmi les roles des utilisateurs FOSuserBundle
		$userDB->removeRole($privilege);
		$userDB->addRole($userDB->getPrivilege());
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Utilisateur modifiee avec Succes');
		return $this->redirect( $this->generateUrl('ajouterUtilisateur') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterUtilisateur') );
		return $this->render('AdminBundle:User:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierUtilisateur', 'bouton'=>'Modifier','user' => $userId)); 	  	
	 }
	 
	 
	 public function prendrePhotoAction($id)
    {
		$userId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_user', $userId);   
		return $this->render('AdminBundle:User:ajouterPhoto.html.twig',array('form' => $form->createView(),'path' => 'modifierUtilisateur', 'bouton'=>'Modifier','user' => $userId)); 	  	
	 }
	 
	 public function checkLoginAction()
    {   
		return $this->render('AdminBundle:User:checkLogin.html.twig',array('path' => 'business_model_login', 'bouton'=>'Check Login'));  	
	 }
	 
	 public function checkRegisterAction()
    {
    	$user= new User();
		$form = $this->createForm('businessmodelbundle_user', $user);   
		return $this->render('AdminBundle:User:checkRegister.html.twig',array('form' => $form->createView(),'path' => 'business_model_register', 'bouton'=>'Check Registration'));  	
	 }
	 
	 
	 public function checkParameters(Request $request,\Symfony\Component\Form\Form $form, $idUser){
		
		//On recupere toutes les donnees du formulaire de name 'businessmodelbundle_user' rempli par l'utilisateur 		
		$registrationArray = $request->get('businessmodelbundle_user');
		
		//On recupere le username et l'email et on verifie que cela ne se trouve pas deja en BD sinon on renvoit le form avec des erreurs
		$username = $registrationArray['username'];
		$email = $registrationArray['email'];
		$userBDByName = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindUsername($username);
		$userBDByEmail = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindEmail($email);
		$error=false;		
		if($userBDByName!=null && $userBDByName->getId()!=$idUser){
		$form->get('username')->addError(new FormError('Nom utilisateur déjà utilisé'));
		$error=true;
		}		
		if($userBDByEmail!=null && $userBDByEmail->getId()!=$idUser){
		$form->get('email')->addError(new FormError('Email utilisateur déjà utilisé'));
		$error=true;
		}		

		//S'il ya erreur on retourne false : les parametres username ou email sont mauvais		
		if($error)
		return false;
		
		return true;
	 }

}
