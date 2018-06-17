<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Activite;
use BusinessModelBundle\Entity\Image;
use BusinessModelBundle\Form\Type\ActiviteType;

class ActiviteController extends Controller
{
	
    public function ajouterAction()
    {
		$activite= new Activite();
		$form = $this->createForm('businessmodelbundle_activite', $activite);   
		return $this->render('AdminBundle:Activite:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerActivite', 'bouton'=>'Enregistrer')); 	  	
	 }

	 public function creerAction()
    {
		$activite= new Activite();
		$image= new Image();
		$form = $this->createForm('businessmodelbundle_activite', $activite); 
		$request = $this->get('request');
		//On recupere l'utilisateur qui est connecte pour le definir comme auteur de l'Activite
		$user= $this->container->get('security.context')->getToken()->getUser();
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		//On lie notre activite a son Auteur
		$activite->setAuteur($user);	
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($activite);
		$em->flush();
		
		 //Je parcours l'objet Activite pour itérer sur les images qui sont passés par le formulaire
            foreach ($activite->getImages() as $i => $img) {
                $img->setActivite($activite);//On lie les images a notre activite
                $em->persist($img);
            }
                $em->flush();
		     $this->get('session')->getFlashBag()->add('info', 'Activite creee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterActivite') );
	 }
	 
	 public function listeAction()
    {
    	     
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }
         
    public function supprimerAction($id)
    {
    	    
		$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($activiteId);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Activite supprimee avec Succes');
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindAll();
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }
    
     public function prendreAction($id)
    {
		$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_activite', $activiteId);   
		return $this->render('AdminBundle:Activite:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierActivite', 'bouton'=>'Modifier','idActivite' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$activite= new Activite();
		$form = $this->createForm('businessmodelbundle_activite', $activite); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$activiteDB=$em->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_activite', $activiteDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$this->get('session')->getFlashBag()->add('info', 'Activite modifiee avec Succes');
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterActivite') );
	 }

    //Fonction speciale permettant de voir les images d'une activite
    public function voirImagesAction($id)
    {
    	$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);     
		$listeImages = $activiteId->getImages();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Image:liste.html.twig',array('listeImages' => $listeImages));
    }
    
    //Fonction speciale permettant de voir les discussions d'une activite
    public function voirDiscussionsAction($id)
    {
    	$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);     
		$listeDiscussions = $activiteId->getDiscussions();
		return $this->render('AdminBundle:Discussion:liste.html.twig',array('listeDiscussions' => $listeDiscussions));
    }


}
