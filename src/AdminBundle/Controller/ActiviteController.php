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
use BusinessModelBundle\Form\Type\SearchActiviteType;
use Symfony\Component\Form\FormError;

class ActiviteController extends Controller
{
	
    public function ajouterAction()
    {
		$activite= new Activite();
		$form = $this->createForm('businessmodelbundle_activite', $activite);   
		return $this->render('AdminBundle:Activite:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerActivite', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
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
		
		if($form->isValid() && $this->checkParameters($request,$form,0)) {
		//On lie notre activite a son Auteur
		$activite->setAuteur($user);	
		// On l'enregistre notre objet $activite dans la base de
		$em = $this->getDoctrine()->getManager();
		//On enregistre d'abord l'image Principale avant l'activite
		//$activite->getImagePrincipale()->setActivite($activite);
      $em->persist($activite->getImagePrincipale());
      $em->flush();
		$em->persist($activite);
		$em->flush();
		
		 //Je parcours l'objet Activite pour itérer sur les images qui sont passés par le formulaire
            foreach ($activite->getImages() as $i => $img) {
                $img->setActivite($activite);//On lie les images a notre activite
                $em->persist($img);
            }
                $em->flush();
           $elt=$this->get('translator')->trans('Barre.Activité.Mot');
		     $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.EnregistrerMessage',array('%elt%' => $elt)));
		     return $this->redirect( $this->generateUrl('ajouterActivite') );
		    }
		    //var_dump($form->getErrorsAsString());
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterActivite') );
		return $this->render('AdminBundle:Activite:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerActivite', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
	 }
	 
	 public function listeAction()
    {
    	     
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindAll();
		//$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindSurActivites
		//("LIMBE",null,null, null,null,"TOURISME","mi",2,0);
		//print_r($listeActivites);
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }
         
    public function supprimerAction($id)
    {
    	    
		$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($activiteId);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Activité.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.SupprimerMessage',array('%elt%' => $elt)));
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindAll();
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }
    
     public function prendreAction($id)
    {
		$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_activite', $activiteId);

		//var_dump($activiteId->getDateEnClair());
		//var_dump($activiteId->getDescriptionEnClair());      
		return $this->render('AdminBundle:Activite:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierActivite', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'activite' => $activiteId)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$activite= new Activite();
		$form = $this->createForm('businessmodelbundle_activite', $activite);
		$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParameters($request,$form,$id)) {
		$em = $this->getDoctrine()->getManager();
		$activiteDB=$em->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_activite', $activiteDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		 //Je parcours l'objet Activite pour itérer sur les images qui sont passés par le formulaire
            foreach ($activiteDB->getImages() as $i => $img) {
                $img->setActivite($activiteDB);//On lie les images a notre activite
					//Si c'est une nouvelle image on enregistre dans la BD                
                if($img->getId()==null)
                $em->persist($img);
            }
                $em->flush();
      $elt=$this->get('translator')->trans('Barre.Activité.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.ModifierMessage',array('%elt%' => $elt)));
		return $this->redirect( $this->generateUrl('ajouterActivite') );
		    }
		    
		}  
		//return $this->redirect( $this->generateUrl('ajouterActivite') );
		return $this->render('AdminBundle:Activite:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierActivite', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'activite' => $activiteId)); 	  	
	 }

    //Fonction speciale permettant de voir les images d'une activite
    public function voirImagesAction($id)
    {
    	$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);     
		$listeImages = $activiteId->getImages();
		//On ajoute aussi l'image principale
		$listeImages[] = $activiteId->getImagePrincipale();
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


 	 //Fonction speciale permettant de rechercher des activites selon des criteres
    public function searchActiviteAction()
    {
    	$listeDest=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindListeDestinations(); 
    	$form = $this->createForm(new SearchActiviteType($listeDest));
		return $this->render('AdminBundle:Activite:rechercher.html.twig',array('form' => $form->createView(),'path' => 'ListeRechercherActivites', 
		'bouton'=> $this->get('translator')->trans('Action.Rechercher')));
    }
    
    //Fonction speciale permettant d'obtenir la liste des activites selon des criteres
    public function searchListActiviteAction()
    {
    	$listeDest=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindListeDestinations(); 
    	$form = $this->createForm(new SearchActiviteType($listeDest));
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParametersSearch($request,$form)) {
		
		$registrationArray = $request->get('businessmodelbundle_searchactivite');
		/*
		$lieuDestination=$listeDest[intval($registrationArray['lieuDestinations'])];		
		$categorie= $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Categorie')->myFindOne(intval($registrationArray['categorie']))->getNom();
		$auteur= $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne(intval($registrationArray['auteur']))->getNomComplet();
		*/
		$lieuDestination=$registrationArray['lieuDestinations'];
		$categorie=$registrationArray['categorie'];
		$auteur=$registrationArray['auteur']; 		
		$dateDebut = $registrationArray['dateDebut'];
		$dateFin = $registrationArray['dateFin'];
		$heureDebut = $registrationArray['heureDebut'];
		$heureFin = $registrationArray['heureFin'];
		$prixIndividuMin = $registrationArray['prixIndividuMin'];
		$prixIndividuMax = $registrationArray['prixIndividuMax'];
		$nbParticipantsMin = $registrationArray['nbParticipantsMin'];
		$nbParticipantsMax = $registrationArray['nbParticipantsMax'];
				
		
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myDeepFindSurActivites(
		$lieuDestination, $dateDebut, $dateFin, $heureDebut, $heureFin, $prixIndividuMin, 
		$prixIndividuMax, $nbParticipantsMin, $nbParticipantsMax, $categorie, $auteur);
		//$listeActivites = array_merge($this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindSurActivites(
		//$lieuDestination, $dateDebut, $dateFin, $heureDebut, $heureFin, $categorie, null,null, null),
		//$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindSurActivites(
		//null, $dateDebut, $dateFin, $heureDebut, $heureFin, $categorie, $lieuDestination,null, null));
    	
    	return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
		}  
		}
		return $this->render('AdminBundle:Activite:rechercher.html.twig',array('form' => $form->createView(),'path' => 'ListeRechercherActivites', 
		'bouton'=>$this->get('translator')->trans('Action.Rechercher')));
    }
    
    //Fonction qui permet de tester s'il les contraintes sur le sur les champs sont respectees
    public function checkParameters(Request $request,\Symfony\Component\Form\Form $form, $idActivite){
    	//On recupere toutes les donnees du formulaire de name 'businessmodelbundle_user' rempli par l'utilisateur 		
		$registrationArray = $request->get('businessmodelbundle_activite');
		
		//On recupere le libelle  et on verifie que cela ne se trouve pas deja en BD sinon on renvoit le form avec des erreurs
		$libelle = $registrationArray['libelle'];
		$activityBDByLibelle = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindByLibelle($libelle);
		$error=false;		
		if($activityBDByLibelle!=null && $activityBDByLibelle->getId()!=$idActivite){
		$form->get('libelle')->addError(new FormError($this->get('translator')->trans('Activité.libelleErrorMessage')));
		$error=true;
		}		
		//S'il ya erreur on retourne false : le parametre libelle est mauvais		
		if($error)
		return false;
		
		return true;
    }
    
    //Fonction qui permet de tester s'il les contraintes de comparaisons sur les champs sont respectees
    public function checkParametersSearch(Request $request,\Symfony\Component\Form\Form $form){
		
		$registrationArray = $request->get('businessmodelbundle_searchactivite');
		
		$dateDebut = ($registrationArray['dateDebut']!=null) ? (new \DateTime($registrationArray['dateDebut'])) : null;
		$dateFin = ($registrationArray['dateFin']!=null) ? (new \DateTime($registrationArray['dateFin'])) : null;
		$heureDebut = ($registrationArray['heureDebut']!=null) ? (new \DateTime($registrationArray['heureDebut'])) : null;
		$heureFin = ($registrationArray['heureFin']!=null) ? (new \DateTime($registrationArray['heureFin'])) : null;
		$prixIndividuMin = ($registrationArray['prixIndividuMin']!=null) ? (floatval($registrationArray['prixIndividuMin'])) : null;
		$prixIndividuMax = ($registrationArray['prixIndividuMax']!=null) ? (floatval($registrationArray['prixIndividuMax'])) : null;
		$nbParticipantsMin = ($registrationArray['nbParticipantsMin']!=null) ? (intval($registrationArray['nbParticipantsMin'])) : null;
		$nbParticipantsMax = ($registrationArray['nbParticipantsMax']!=null) ? (intval($registrationArray['nbParticipantsMax'])) : null;

		$error=false;		
		if(($dateDebut!=null && $dateFin !=null) && ($dateDebut > $dateFin)){
		$form->get('dateDebut')->addError(new FormError($this->get('translator')->trans('Activité.dateDebutErrorMessage')));
		$form->get('dateFin')->addError(new FormError($this->get('translator')->trans('Activité.dateDebutErrorMessage')));
		$error=true;
		}
		
		if(($heureDebut!=null && $heureFin !=null) && ($heureDebut > $heureFin)){
		$form->get('heureDebut')->addError(new FormError($this->get('translator')->trans('Activité.heureDebutErrorMessage')));
		$form->get('heureFin')->addError(new FormError($this->get('translator')->trans('Activité.heureDebutErrorMessage')));
		$error=true;
		}
		
		if(($prixIndividuMin!=null && $prixIndividuMax !=null) && ($prixIndividuMin > $prixIndividuMax)){
		$form->get('prixIndividuMin')->addError(new FormError($this->get('translator')->trans('Activité.prixIndividuMinErrorMessage')));
		$form->get('prixIndividuMax')->addError(new FormError($this->get('translator')->trans('Activité.prixIndividuMinErrorMessage')));
		$error=true;
		}
		
		if(($nbParticipantsMin!=null && $nbParticipantsMax !=null) && ($nbParticipantsMin > $nbParticipantsMax)){
		$form->get('nbParticipantsMin')->addError(new FormError($this->get('translator')->trans('Activité.nbParticipantsMinErrorMessage')));
		$form->get('nbParticipantsMax')->addError(new FormError($this->get('translator')->trans('Activité.nbParticipantsMinErrorMessage')));
		$error=true;
		}
				
		//S'il ya erreur on retourne false : les parametres sont mauvais		
		if($error)
		return false;
		
		return true;
	 }

}
