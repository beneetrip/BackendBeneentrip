<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Reservation;
use BusinessModelBundle\Form\Type\ReservationType;
use BusinessModelBundle\Form\Type\SearchReservationType;
use BusinessModelBundle\Form\Type\PrintType;
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
		
		if($form->isValid()) {
		// On l'enregistre notre objet $user dans la base de
		$em = $this->getDoctrine()->getManager();
		$em->persist($reservation);
		$em->flush();
		     $elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		     $this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.EnregistrerMessage',array('%elt%' => $elt)));
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterReservation') );
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
		$listePages = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindAll();
		return $this->render('AdminBundle:Reservation:liste.html.twig',array('listeReservations' => $listeReservations));
    }
    
     public function prendreAction($id)
    {
		$reservationId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_reservation', $reservationId);   
		return $this->render('AdminBundle:Reservation:ajouter.html.twig',array('form' => $form->createView(),'path' => 'modifierReservation', 'bouton'=>
		$this->get('translator')->trans('Action.Modifier'),'idReservation' => $id)); 	  	
	 }
	 
	 public function modifierAction($id)
    {
		$reservation= new Reservation();
		$form = $this->createForm('businessmodelbundle_reservation', $reservation); 
		$request = $this->get('request');
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		
		if($form->isValid()) {
		$em = $this->getDoctrine()->getManager();
		$reservationDB=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne($id);
		$form = $this->createForm('businessmodelbundle_reservation', $reservationDB);
		// À partir de maintenant, la variable $userDB contient les valeurs entrées dans le formulaire par le visiteur
		$form->bind($request);
		$em->flush();
		$elt=$this->get('translator')->trans('Barre.Réservation.Mot');
		$this->get('session')->getFlashBag()->add('info', $this->get('translator')->trans('Action.ModifierMessage',array('%elt%' => $elt)));
		    }
		    
		}  
		return $this->redirect( $this->generateUrl('ajouterReservation') );
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
		
		if($form->isValid()) {
		
		$registrationArray = $request->get('businessmodelbundle_searchreservation');
		
		$utilisateurs=$registrationArray['utilisateurs'];
		$activites=$registrationArray['activites'];
		//Si on a selectionne l'option de valeur 0 du select alors ce n'est ni oui ni non paye est equivalent a null
		$paye=($registrationArray['paye'] =='2')? null: $registrationArray['paye'];
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations
		($activites, $utilisateurs, $paye);
    	
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
		
		if($form->isValid()) {
		
		$registrationArray = $request->get('businessmodelbundle_print');
		
		$utilisateurs=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindOne(intval($registrationArray['utilisateurs']))->getNomComplet();
		
		//Si on a selectionne l'option de valeur 0 du select alors ce n'est ni oui ni non paye est equivalent a null
		$paye=($registrationArray['paye'] =='2')? null: $registrationArray['paye'];
		
		//var_dump($utilisateurs);
    	//var_dump($this->genererFactureHTML($listeReservations));
    	$this->creerFacturePDF($this->genererFactureCodeHTML($utilisateurs,$paye),$this->genererFactureCodeCSS());
return $this->render('AdminBundle:Reservation:print.html.twig',array('form' => $form->createView(),'path' => 'imprimerPDF', 
		'bouton'=> $this->get('translator')->trans('Action.Imprimer')));
		}  
		}
return $this->render('AdminBundle:Reservation:print.html.twig',array('form' => $form->createView(),'path' => 'imprimerPDF', 
		'bouton'=> $this->get('translator')->trans('Action.Imprimer')));
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
		
		
		
		function genererFactureCodeHTML($nomUser, $paye)
		{
		
		//IMPORTANT : On va utiliser des quotes simples au lieu des quotes doubles ceci a cause du HTML qu'on va generer pour envoyer a l'impression
		
		//En general dans le cas des generations des code HTML la double quote doit etre toujours interne a la simple quotes
		
		//La balise <SUP> pour mettre en exposant et <SUB> pour mettre en indice
		
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations
		(null, $nomUser, $paye);
		
		$nowDate=new \DateTime();	
		
		$html='<div class="fond">';
		
		$html=$html.'<h1>FACTURE N<SUP>o</SUP> '.$nowDate->format('dmYHis').' DE '.strtoupper($nomUser).'</h1><br/><br/><br/><br/>'.
		'<table>'.
		'<thead>'.
		'<tr>'.
		'<th>Activite</th>'.
		'<th>Destination</th>'.
		'<th>Date</th>'.
		'<th>Heure</th>'.
		'<th>Prix</th>'.
		'<th>Paye</th>'.
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
		$html=$html. '<tr>'.
		'<td colspan="6">';
		$html=$html.'MONTANT TOTAL : '.$this->calculerMontantTotal($listeReservations).
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
			h1{
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
