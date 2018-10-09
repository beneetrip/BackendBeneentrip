<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Reservation;
use BusinessModelBundle\Entity\Payment;
use BusinessModelBundle\Form\Type\ReservationType;
use BusinessModelBundle\Form\Type\SearchReservationType;
use BusinessModelBundle\Form\Type\PrintType;
use BusinessModelBundle\Form\Type\HistoryType;
use Symfony\Component\Form\FormError;
use BusinessModelBundle\Entity\PDFInvoice;

class ReservationController extends Controller
{
	
    public function ajouterAction()
    {
		$reservation= new Reservation();
		$form = $this->createForm('businessmodelbundle_reservation', $reservation);   
		return $this->render('AdminBundle:Reservation:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerReservation', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
	 }

	 public function creerAction()
    {
		$reservation= new Reservation();
		$form = $this->createForm('businessmodelbundle_reservation', $reservation); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParameters($request,$form,$reservation,0)) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($reservation);
		$em->flush();
		     $elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		     $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.EnregistrerMessage',array('%elt%' => $elt)));
		     return $this->redirect( $this->generateUrl('ajouterReservation') );
		    }
		    
		}  
		return $this->render('AdminBundle:Reservation:ajouter.html.twig',array('form' => $form->createView(),'path' => 'creerReservation', 'bouton'=>
		$this->get('translator')->trans('Action.Enregistrer'))); 	  	
	 }
	 
	 public function listeAction()
    {
    	 
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindAll();
		// L'appel de la vue ne change pas
		return $this->render('AdminBundle:Reservation:liste.html.twig',array('listeReservations' => $listeReservations));
    }
         
    public function supprimerAction($id)
    {
    	    
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$em=$this->getDoctrine()->getManager();
		$em->remove($reservationId);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.SupprimerMessage',array('%elt%' => $elt)));
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindAll();
		return $this->render('AdminBundle:Reservation:liste.html.twig',array('listeReservations' => $listeReservations));
    }
    
     public function prendreAction($id)
    {
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_reservation', $reservationId);   
		return $this->render('AdminBundle:Reservation:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierReservation', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'reservation' => $reservationId)); 	  	
	 }
	 
	 public function paiementStripeAction($id)
    {
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		return $this->render('AdminBundle:Reservation:stripe.html.twig',array('reservation' => $reservationId));	
    } 
	 
	 public function modifierAction($id)
    {
		$reservation= new Reservation();
		$form = $this->createForm('businessmodelbundle_reservation', $reservation);
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		if($form->isValid() && $this->checkParameters($request,$form,$reservation,$id)) {
		$em = $this->getDoctrine()->getManager();
		$reservationDB=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_reservation', $reservationDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.ModifierMessage',array('%elt%' => $elt)));
		//return $this->redirect( $this->generateUrl('ajouterReservation') );
		    }
		    
		}  
		return $this->render('AdminBundle:Reservation:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierReservation', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'reservation' => $reservationId));
	 }
	 
	 //Fonction speciale permettant de voir l'activite d'une reservation
    public function voirActiviteAction($id)
    {
		$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		$listeActivites=[];
		$listeActivites[] = $activiteId;
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }


	//Fonction speciale permettant de rechercher des reservations selon des criteres
    public function searchReservationAction()
    {
    	//On construit la liste de choix avec les traduction pour le champ paye?
    	$listeChoix=array(
    	'0'=>$this->get('translator')->trans('Réservation.nonOk'),
    	'1'=>$this->get('translator')->trans('Réservation.Ok'),
    	'2'=>'---'
    	);
    	$form = $this->createForm(new SearchReservationType($listeChoix));
		return $this->render('AdminBundle:Reservation:rechercher.html.twig',array('form' => $form->createView(),'path' => 'ListeRechercherReservations', 
		'bouton'=> $this->get('translator')->trans('Action.Rechercher')));
    }
    
    //Fonction speciale permettant d'obtenir la liste des reservations selon des criteres
    public function searchListReservationAction()
    {
    	//On construit la liste de choix avec les traduction pour le champ paye?
		$listeChoix=array(
    	'0'=>$this->get('translator')->trans('Réservation.nonOk'),
    	'1'=>$this->get('translator')->trans('Réservation.Ok'),
    	'2'=>'---'
    	);

    	$form = $this->createForm(new SearchReservationType($listeChoix));
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParametersSearchPrint($request,$form,true)) {
		
		$registrationArray = $request->get('businessmodelbundle_searchreservation');
		
		$utilisateurs=$registrationArray['utilisateurs'];
		$activites=$registrationArray['activites'];
		$dateDebut = $registrationArray['dateDebut'];
		$dateFin = $registrationArray['dateFin'];
		//Si on a selectionne l'option de valeur 0 du select alors ce n'est ni oui ni non paye est equivalent a null
		$paye=($registrationArray['paye'] =='2')? null: $registrationArray['paye'];
		
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations
		($activites, $utilisateurs, $paye, $dateDebut, $dateFin);
    	//var_dump($dateDebut);
    	//var_dump($dateFin);
    	return $this->render('AdminBundle:Reservation:liste.html.twig',array('listeReservations' => $listeReservations));
		}  
		}
		return $this->render('AdminBundle:Reservation:rechercher.html.twig',array('form' => $form->createView(),'path' => 'ListeRechercherReservations', 
		'bouton'=>$this->get('translator')->trans('Action.Rechercher')));
    }
    
    //Fonction speciale permettant d'imprimer des factures de reservations selon des criteres
    public function printReservationAction()
    {
    	//On construit la liste de choix avec les traduction pour le champ paye?
    	$listeChoix=array(
    	'0'=>"Guide",
    	'1'=>"Touriste"
    	);
    	$form = $this->createForm(new PrintType($listeChoix));
		return $this->render('AdminBundle:Reservation:print.html.twig',array('form' => $form->createView(),'path' => 'imprimerPDF', 
		'bouton'=> $this->get('translator')->trans('Action.Imprimer')));
    }
    
    //Fonction speciale permettant d'imprimer le PDF des factures de reservations selon des criteres
    public function printPDFAction()
    {
    	//On construit la liste de choix avec les traduction pour le champ paye?
		$listeChoix=array(
    	'0'=>"Guide",
    	'1'=>"Touriste"
    	);

    	$form = $this->createForm(new PrintType($listeChoix));
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParametersSearchPrint($request,$form,false) ) {
		
		$registrationArray = $request->get('businessmodelbundle_print');
		
		$user=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne(intval($registrationArray['utilisateurs']));
		
		$dateDebut = $registrationArray['dateDebut'];
		$dateFin = $registrationArray['dateFin'];		
		
		//Si on a selectionne l'option de valeur 0 du select alors c'est un guide sinon c'est un touriste
		$type=$registrationArray['type'];
		

		$pdfInvoice=new PDFInvoice();
		

		$payments=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Payment')->myFindPayments($user->getNom(), $type, $dateDebut, $dateFin);
		
		if(count($payments)>0){
		//Si c'est un touriste on le genere la derniere facture de la periode selectionnee
		if($type=='1'){
		$payment=$payments[(count($payments)-1)];
		//echo $pdfInvoice->genererInvoiceCodeHTML($user,$payment);
	   $file=__DIR__.'/../../../web/'.$pdfInvoice->genererPDFWithTCPDF($pdfInvoice->genererInvoiceCodeHTML($user,$payment),PDFInvoice::genererCodeCSS(), true);
		}
		//C'est un guide
		else{
		$file=__DIR__.'/../../../web/'.$pdfInvoice->genererPDFWithTCPDF($pdfInvoice->genererStatementCodeHTML($user,$payments),PDFInvoice::genererCodeCSS(), false);
		}
		
		//On afficher le telechargement automatique du fichier PDF creee 
	   if (file_exists($file)) {
	 		 	
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Length: ' . filesize($file));
            //ob_clean();
            //flush();
            readfile($file);
            //exit;
      }
		}
		return $this->render('AdminBundle:Reservation:print.html.twig',array('form' => $form->createView(),'path' => 'imprimerPDF', 
		'bouton'=> $this->get('translator')->trans('Action.Imprimer')));
		}  
		}
		
		return $this->render('AdminBundle:Reservation:print.html.twig',array('form' => $form->createView(),'path' => 'imprimerPDF', 
		'bouton'=> $this->get('translator')->trans('Action.Imprimer')));
    }
    
     //Fonction speciale permettant des recherches les historiques sur reservations selon des criteres
    public function historyReservationAction()
    {
		
		$listeChoix=array(
    	'0'=>"Guide",
    	'1'=>"Touriste"
    	);
    	$listeChoix2=array(
    	'0'=>$this->get('translator')->trans('Réservation.Passees'),
    	'1'=>$this->get('translator')->trans('Réservation.EnCours'),
    	'2'=>$this->get('translator')->trans('Réservation.Avenir')
    	);
    	$form = $this->createForm(new HistoryType($listeChoix,$listeChoix2));
		return $this->render('AdminBundle:Reservation:history.html.twig',array('form' => $form->createView(),'path' => 'listeHistoriqueReservations', 
		'bouton'=> $this->get('translator')->trans('Action.History')));
    }
    
    //Fonction speciale permettant d'avoir la liste des historiques sur reservations
    public function listHistoryReservationAction()
    {
		$listeChoix=array(
    	'0'=>"Guide",
    	'1'=>"Touriste"
    	);
    	$listeChoix2=array(
    	'0'=>$this->get('translator')->trans('Réservation.Passees'),
    	'1'=>$this->get('translator')->trans('Réservation.EnCours'),
    	'2'=>$this->get('translator')->trans('Réservation.Avenir')
    	);

    	$form = $this->createForm(new HistoryType($listeChoix,$listeChoix2));
    	$request = $this->get('request');
    	$listeReservations=array();
    	if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		
		$registrationArray = $request->get('businessmodelbundle_history');
		
		$utilisateurs=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne(intval($registrationArray['utilisateurs']))->getNom();
			
	
		$evenement=$registrationArray['evenement'];
		
		$type=$registrationArray['type'];
		
		
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindHistoriqueSurReservations(
		$utilisateurs,$type, $evenement);
		
		
    	//print_r($listeReservations);
		}  
		}
		
		//return $this->render('AdminBundle:Reservation:liste.html.twig',array('listeReservations' => $listeReservations));
		return $this->render('AdminBundle:Activite:liste.html.twig',array('listeActivites' => $listeActivites));
    }
    
    
		
		//Fonction qui permet de controler les resevervations sur les utilisateurs avant insertion ou modification
		//Elle retourne un tableau contenant le resultat, un boolean; l'activite et l'utilisateur qui a cree le pb: 
		//  array("resultat"=>val1, "activite"=>val2, "utilisateur"=> val3)
		public function EstBonneReservation($reservation,$idParam)
		{
    			
    			$retour=array("resultat"=>true,"activite"=>null,"utilisateur"=> null);				    			

    			foreach($reservation->getActivites() as $activite)	{
    				
    			$nbParticipantsActivite=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')
    			->nombreReservationsDeja(null,$activite->getId(),null);	
    				
    			foreach($reservation->getUtilisateurs() as $utilisateur){
    				
				$nbParticipantsActiviteUtilisateur=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')
    			->nombreReservationsDeja(null,$activite->getId(),$utilisateur->getId());


				if($idParam==0)//On sait qu'il s'agit de l'insertion d'une nouvelle reservation
				{
				//Si l'utilisateur a deja reserve l'activite on genere l'erreur
				if($nbParticipantsActiviteUtilisateur>0)
				return array("resultat"=>false,"activite"=>$activite,"utilisateur"=> $utilisateur);
				//Sinon si le quota des activites est atteint on genere l'erreur
				else if($activite->getNbParticipants()<=$nbParticipantsActivite)
				return array("resultat"=>false,"activite"=>$activite,"utilisateur"=> null);    						
    			}
    			else//On sait qu'il s'agit d'une modification d'une reservation
    			{
				
    			$nbParticipantsActiviteUtilisateurDeja=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')
    			->nombreReservationsDeja($idParam,$activite->getId(),$utilisateur->getId());
    			
    			$nbParticipantsActiviteDeja=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')
    			->nombreReservationsDeja($idParam,$activite->getId(),null);
    			
				//Si C'est un nouvel utilisateur dans la reservation et que l'utilisateur a deja reserve l'activite on genere l'erreur
				if($nbParticipantsActiviteUtilisateurDeja<1 && $nbParticipantsActiviteUtilisateur>0)
				return array("resultat"=>false,"activite"=>$activite,"utilisateur"=> $utilisateur);
				
				//Si si c'est un nouvel utilisateur dans la reservation et que le quota de l'activites est atteint on genere l'erreur
				if($nbParticipantsActiviteUtilisateurDeja<1 && $activite->getNbParticipants()<=$nbParticipantsActivite)
				return array("resultat"=>false,"activite"=>$activite,"utilisateur"=> null);
				
				//Sinon si c'est une nouvelle activite et que le quota de l'activites est atteint on genere l'erreur
				else if($nbParticipantsActiviteDeja<1 && $activite->getNbParticipants()<=$nbParticipantsActivite)
				return array("resultat"=>false,"activite"=>$activite,"utilisateur"=> null);    						    						
    			}    				
    				  			
    			}
    			
    			}

    			return $retour; 
		}    
    
    public function checkParameters(Request $request,\Symfony\Component\Form\Form $form, $reservation, $idParam){
    		
    		$retour=$this->EstBonneReservation($reservation,$idParam);
    		
    		if(!$retour["resultat"] && $retour["utilisateur"]==null){
    		$form->get('activites')->addError(new FormError(
    		$this->get('translator')->trans('Réservation.quotaErrorMessage',array('%activite%' => $retour["activite"]->getLibelle(),'%quota%' => $retour["activite"]->getNbParticipants()))));
    		}
    		else if(!$retour["resultat"] && $retour["utilisateur"]!=null){
    		$form->get('utilisateurs')->addError(new FormError(
    		$this->get('translator')->trans('Réservation.reserveErrorMessage',
    		array('%activite%' => $retour["activite"]->getLibelle(),'%utilisateur%' => $retour["utilisateur"]->getNomComplet()))));
    		}
    		
    		//S'il ya erreur on retourne false : les parametres sont mauvais		
			if(!$retour["resultat"])
			return false;
			
			return true;
    }
    
    	//Fonction qui permet de tester s'il les contraintes de comparaisons sur les champs sont respectees
    	//le parametres estformsearch nous indique si la verification concerne un formulaire de recherche sur reservation ou pas
    public function checkParametersSearchPrint(Request $request,\Symfony\Component\Form\Form $form, $estformsearch){
		
		if($estformsearch)
		$registrationArray = $request->get('businessmodelbundle_searchreservation');
		else 		
		$registrationArray = $request->get('businessmodelbundle_print');
		
		$dateDebut = ($registrationArray['dateDebut']!=null) ? (new \DateTime($registrationArray['dateDebut'])) : null;
		$dateFin = ($registrationArray['dateFin']!=null) ? (new \DateTime($registrationArray['dateFin'])) : null;

		$error=false;		
		if(($dateDebut!=null && $dateFin !=null) && ($dateDebut > $dateFin)){
		$form->get('dateDebut')->addError(new FormError($this->get('translator')->trans('Activité.dateDebutErrorMessage')));
		$form->get('dateFin')->addError(new FormError($this->get('translator')->trans('Activité.dateDebutErrorMessage')));
		$error=true;
		}
				
		//S'il ya erreur on retourne false : les parametres sont mauvais		
		if($error)
		return false;
		
		return true;
	 }
    
    	function calculerMontantTotal($listeReservations)
    	{
    			$somme=0.0;
    			foreach($listeReservations as $reservation){
    				
    			foreach($reservation->getActivites() as $activite)	
    			$somme+=floatval($activite->getPrixIndividu());	
    			
    			}
    			return $somme;	
    	} 
		 
}
