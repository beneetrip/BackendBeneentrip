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
use BusinessModelBundle\Form\Type\SearchDiscussionType;

class DiscussionController extends Controller
{
	
    public function ajouterAction()
    {
		$discussion= new Discussion();
		$form = $this->createForm('businessmodelbundle_discussion', $discussion);   
		return $this->render('AdminBundle:Discussion:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerDiscussion', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
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
		     $elt=$this->get('translator')->trans('Barre.Discussion.Mot');
		     $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.EnregistrerMessage',array('%elt%' => $elt)));
		     return $this->redirect( $this->generateUrl('ajouterDiscussion') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterDiscussion') );
		return $this->render('AdminBundle:Discussion:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerDiscussion', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
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
		$elt=$this->get('translator')->trans('Barre.Discussion.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.SupprimerMessage',array('%elt%' => $elt)));
		$listeDiscussions = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindAll();
		return $this->render('AdminBundle:Discussion:liste.html.twig',array('listeDiscussions' => $listeDiscussions));
    }
    
    public function prendreAction($id)
    {
		$discussionId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_discussion', $discussionId);   
		return $this->render('AdminBundle:Discussion:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierDiscussion', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'idDiscussion' => $id)); 	  	
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
		$elt=$this->get('translator')->trans('Barre.Discussion.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.ModifierMessage',array('%elt%' => $elt)));
		return $this->redirect( $this->generateUrl('ajouterDiscussion') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterDiscussion') );
		return $this->render('AdminBundle:Discussion:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierDiscussion', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'idDiscussion' => $id)); 	  	
	 }
	 
	 //Fonction speciale permettant de voir l'activite d'une discussion
    public function voirActiviteAction($id)
    {
		$discussionId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindOne($id);
		$listeActivites=[];
		$listeActivites[] = $discussionId->getActivite();
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }
    
    //Fonction speciale permettant de rechercher des discussions selon des criteres
    public function searchDiscussionAction()
    {
    	$listeChoix=array(
    	'0'=>'Discussion',
    	'1'=>'Report',
    	'2'=>'---'
    	);
    	$form = $this->createForm(new SearchDiscussionType($listeChoix));
		return $this->render('AdminBundle:Discussion:rechercher.html.twig',array('form' => $form->createView(),'path' => 'ListeRechercherDiscussions', 
		'bouton'=> $this->get('translator')->trans('Action.Rechercher')));
    }
    
    
    //Fonction speciale permettant d'obtenir la liste des discussions selon des criteres
    public function searchListDiscussionAction()
    {
    	//On construit la liste de choix avec les traduction pour le champ paye?
		$listeChoix=array(
    	'0'=>'Discussion',
    	'1'=>'Report',
    	'2'=>'---'
    	);

    	$form = $this->createForm(new SearchDiscussionType($listeChoix));
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		
		$registrationArray = $request->get('businessmodelbundle_searchdiscussion');
		
		$auteur=$registrationArray['auteur'];
		$activite=$registrationArray['activite'];
		$destinataires = $registrationArray['destinataires'];
		
		if($registrationArray['type'] =='0')
		$type="Discussion";
		else if($registrationArray['type'] =='1')
		$type="Report";
		else 
		$type=null;

		$listeDiscussions = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Discussion')->myFindSurDiscussions
		($auteur, $activite, $destinataires, $type);
    	
    	return $this->render('AdminBundle:Discussion:liste.html.twig',array('listeDiscussions' => $listeDiscussions));
		}  
		}
		return $this->render('AdminBundle:Discussion:rechercher.html.twig',array('form' => $form->createView(),'path' => 'ListeRechercherDiscussions', 
		'bouton'=>$this->get('translator')->trans('Action.Rechercher')));
    }


}
