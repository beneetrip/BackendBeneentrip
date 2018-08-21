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
use Symfony\Component\Form\FormError;
//use BusinessModelBundle\Util\tcpdf\TCPDF;

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
    	 /*    
		//Comme il est possible qu'on nous retourne apres un transaction d'achat sur paypal, on va donc tester notre transaction si elle a bien eu lieu
		if(isset($_GET) && count($_GET)>0){		
		$item_no            = $_GET['item_number'];//Id du produit de reservation
		$item_transaction   = $_GET['tx']; // Paypal transaction ID
		$item_price         = $_GET['amt']; // Paypal received amount
		$item_currency      = $_GET['cc']; // Paypal received currency type
		$item_transaction_status= $_GET['st']; // Paypal received currency type
		$id_user 			  = $_GET['cm'];// ID of the customer

		
		$em = $this->getDoctrine()->getManager();
		$reservationId=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne(intval($item_no));
		$userId=$em->getRepository('BusinessModelBundle:User')->myFindOne(intval($id_user));
		$elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		//On controle les donnees envoyees par Paypal en cas de transaction ok on met a jour notre reservation en mettant etat paye a true
		if($reservationId!=null && $item_price>=$reservationId->calculerMontantTotal() && $item_currency=="EUR" && strtoupper($item_transaction_status)=="COMPLETED"){
		
		$reservationId->setPaye(true);
		
		$payment=new Payment();
	   $payment->setItemId($item_no);
	   $payment->setTransactionId($item_transaction);
	   $payment->setAmount($item_price);
	   $payment->setCurrencyCode($item_currency);
	   $payment->setStatus(strtoupper($item_transaction_status));
	   $payment->setUtilisateur($userId);
	   
	   $em->persist($payment);
		$em->flush();
		
		$this->get('session')->getFlashBag()->add('paypalSuccess', $this->get('translator')->trans('Réservation.success',array('%elt%' => $elt)));
		}else
		$this->get('session')->getFlashBag()->add('paypalFailure', $this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)));
		}*/
		
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
    	'0'=>$this->get('translator')->trans('Réservation.nonOk'),
    	'1'=>$this->get('translator')->trans('Réservation.Ok'),
    	'2'=>'---'
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
    	'0'=>$this->get('translator')->trans('Réservation.nonOk'),
    	'1'=>$this->get('translator')->trans('Réservation.Ok'),
    	'2'=>'---'
    	);

    	$form = $this->createForm(new PrintType($listeChoix));
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid() && $this->checkParametersSearchPrint($request,$form,false) ) {
		
		$registrationArray = $request->get('businessmodelbundle_print');
		
		$utilisateurs=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne(intval($registrationArray['utilisateurs']))->getNom();
		
		$dateDebut = $registrationArray['dateDebut'];
		$dateFin = $registrationArray['dateFin'];		
		
		//Si on a selectionne l'option de valeur 0 du select alors ce n'est ni oui ni non paye est equivalent a null
		$paye=($registrationArray['paye'] =='2')? null: $registrationArray['paye'];
		
    	/*$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations
		(null, $utilisateurs, $paye, $dateDebut, $dateFin);
    	print_r($listeReservations);*/
    	$this->creerFacturePDF($this->genererFactureCodeHTML($utilisateurs,$dateDebut,$dateFin,$paye),$this->genererFactureCodeCSS());
		
		return $this->render('AdminBundle:Reservation:print.html.twig',array('form' => $form->createView(),'path' => 'imprimerPDF', 
		'bouton'=> $this->get('translator')->trans('Action.Imprimer')));
		}  
		}
		
		return $this->render('AdminBundle:Reservation:print.html.twig',array('form' => $form->createView(),'path' => 'imprimerPDF', 
		'bouton'=> $this->get('translator')->trans('Action.Imprimer')));
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
		
		
		
		function genererFactureCodeHTML($nomUser, $dateDebut, $dateFin, $paye)
		{
		
		//IMPORTANT : On va utiliser des quotes simples au lieu des quotes doubles ceci a cause du HTML qu'on va generer pour envoyer a l'impression
		
		//En general dans le cas des generations des code HTML la double quote doit etre toujours interne a la simple quotes
		
		//La balise <SUP> pour mettre en exposant et <SUB> pour mettre en indice
		
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations
		(null, $nomUser, $paye, $dateDebut, $dateFin);
		
		$nowDate=new \DateTime();	

		if(isset($dateDebut) && trim($dateDebut,'')!=""){
		$date1=new \DateTime($dateDebut);
		$dateDebut=$date1->format('d/m/Y');		
		}
		else 
		$dateDebut="---";
		
		if(isset($dateFin) && trim($dateFin,'')!=""){
		$date2=new \DateTime($dateFin);
		$dateFin=$date2->format('d/m/Y');		
		}
		else		
		$dateFin="---";		
		
		$html='<div class="fond">';
		
		$html=$html.'<h1>'.$this->get('translator')->trans('Facture.mot').' '.strtoupper($nomUser).' N<SUP>o</SUP> '.$nowDate->format('dmYHis').'</h1><br/><br/><br/><br/>'.
		$html=$html.'<h4>'.$this->get('translator')->trans('Facture.debut').' '.$dateDebut.' '.
		$this->get('translator')->trans('Facture.fin').' '.$dateFin.'</h4><br/><br/><br/><br/>'.		
		'<table>'.
		'<thead>'.
		'<tr>'.
		'<th>'.$this->get('translator')->trans('Barre.Activité.Mot').'</th>'.
		'<th>'.$this->get('translator')->trans('Activité.lieuDestination').'</th>'.
		'<th>'.$this->get('translator')->trans('Activité.date').'</th>'.
		'<th>'.$this->get('translator')->trans('Activité.heure').'</th>'.
		'<th>'.$this->get('translator')->trans('Activité.prixIndividu').'</th>'.
		'<th>'.$this->get('translator')->trans('Réservation.paye').'</th>'.
		'</tr>'.
		'</thead>';
		
		foreach($listeReservations as $reservation){
		
		foreach($reservation->getActivites() as $activite)	{	
		$html=$html.'<tr>';
		$html=$html. '<td>';
		$html=$html. $activite->getLibelle();
		$html=$html. '</td>'.
		'<td>';
		$html=$html. $activite->getLieuDestination();
		$html=$html. '</td>'.
		'<td>';
		$html=$html. $activite->getDateEnClair();
		$html=$html. '</td>'.
		'<td>';
		$html=$html. date_format($activite->getHeure(),'H:i');
		$html=$html. '</td>'.
		'<td>';
		$html=$html. $activite->getPrixIndividu();
		$html=$html. '</td>'.
		'<td>';
		$html=$html. (($reservation->getPaye()) ? $this->get('translator')->trans('Réservation.Ok') : $this->get('translator')->trans('Réservation.nonOk'));
		$html=$html. '</td>'.
		'</tr>';
		}
		
		}
		$html=$html. '<tr class="totalRow">'.
		'<td colspan="4">';
		$html=$html.' '.$this->get('translator')->trans('Facture.total').' : '.
		'</td>'.
		'<td colspan="2">'.$this->calculerMontantTotal($listeReservations).
		'</td>'.
		'</tr>';
		$html=$html. '</table>'.
		"</div>";
		
		//return htmlspecialchars($html);//cette fonction nous htmlne le brut HTML en remplacant certains caracteres en code HTML: & devient &amp;
		return $html;
		}
		
		static function genererFactureCodeCSS()
		{		

$css=<<<EOF
			<style>
			
			table
			{
			width: 100%;
			font-size: 14px;
			font-family: times news roman;
			}
			
			table tr th
			{
			background-color: white;
			font-weight: bold;
			text-align: center;
			height: 50px;
			font-size: 18px;
			border: 1px solid black;
			}
			
			td{
			border: 1px solid black;
			text-align: center;
			}
			
			tr.totalRow{
			background-color:white;
			font-weight: bold;
			text-align: center;
			height: 50px;
			font-size: 18px;
			font-style: italic;
			}
			
			.fond{
			background-color: #fff;
			}
			h1, h4{
			text-align: center;
			}
			</style>
EOF;

      return $css;
		}
		
		
		//fonction permettant de creer la facture PDF a partir des templates HTML et CSS
		function creerFacturePDF($html,$css){
	    
	 require_once(__DIR__.'/../../../src/AdminBundle/Resources/public/tcpdf/tcpdf.php');
 	 
 	 $lang='en';
    
    // create new PDF document
    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('BeneenTrip');
    $pdf->SetTitle('BeneenTrip');
    $pdf->SetSubject('BeneenTrip');
    //$pdf->SetKeywords('TCPDF, PDF, tab, appphp, print');
 
    // set default header data
   $logo='../../../../../../AdminBundle/Resources/public/img/logoBeneenTrip.png';
   $logowidth=15;
   $title="BeneenTrip";
   //On recupere la date en cours
   $nowDate=new \DateTime();
   $string="Date : ".$nowDate->format('d/m/Y H:i:s');
   //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
	$pdf->SetHeaderData($logo, $logowidth,$title, $string);
    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
    //set margins
    //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
    //set some language-dependent strings
    $pdf->setLanguageArray($lang);
 
    // set font
    $pdf->SetFont('helvetica', '', 10);
 
    // add a page
    $pdf->AddPage();

	//$codefinal=htmlspecialchars($css.''.$html);
    
    $pdf->writeHTML($css.''.$html, true, false, true, false, '');
    
    // reset pointer to the last page
    $pdf->lastPage();
    
     ob_end_clean(); //add this line here to avoid TCPDF ERROR: Some data has already been output, can't send PDF file
    
    //Close, output PDF document
    //$pdf->Output('monDocPrint.pdf', 'I');
    //Close, save PDF document on disk
    $nowDate=new \DateTime();	
    $dir=__DIR__.'/../../../web/invoices/'.$nowDate->format('d/m/Y/H');
    if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
		}
	 $file=$dir.'/'.$nowDate->format('dmYHis').'.pdf';
   $pdf->Output($file, 'F');
	
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

}
