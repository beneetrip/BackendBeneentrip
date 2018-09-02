<?php

namespace MobileAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Payment;
use BusinessModelBundle\Entity\PaypalAPI;

class PaymentController extends Controller
{
	
      public function creerPaiementPaypalAction()
      {
      $postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		
		//$idUser=2;
	
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNom(),'0',null,null);
		
		//S'il ya pas de reservation non paye de l'utilisateur
		if(count($listeRetour)<=0){
			
		$response = new Response(json_encode(array(
		'failure'=>'Le panier n\'existe pas'
		)));	
		}
		
		else{		
		
		$reservationId = $listeRetour[0];
		
		

		$amountTotal=$reservationId->calculerMontantTotalAvecTaxe();
    	$amount=$reservationId->calculerMontantTotal();
    	//$amountTax=$reservationId->calculerMontantTaxe();
		$amountTax=$reservationId->calculerMontantTaxeActiviteTotal();
    	$currencyCode="EUR";
    	
    	//On teste si la somme totale est nulle car ainsi pas besoin d'aller chez paypal on fait les mise a jour et on renvoit immediatement la reponse
		if($amountTotal==0.0)
		{
			
		$reservationId->setPaye(true);
		$em->flush();
			
		//On enregistre le payment dans la BD et on met a jour la reservation de l'utilisateur: elle est reglee
		 $payment=new Payment();
		 $payment->setItemId($reservationId->getId());
		 $payment->setAmount($amountTotal);
		 $payment->setStatus(strtoupper("COMPLETED"));
		 $payment->setCurrencyCode($currencyCode);
		 $payment->setUtilisateur($userId);
		 
		 $payment->setInvoice($this->creerFacturePDF($this->genererFactureCodeHTML($userId->getId(),$reservationId->getId()),
    	 $this->genererFactureCodeCSS()));
		 
		 $em->persist($payment);
		 $em->flush();	
		 //On renvoit la reponse avec success
		 $response = new Response(json_encode(array('success'=>'Paiement effectué avec succès')));
		 $response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;	
		}
		
		
		$config = array(
    'clientId' => 'AXJ9Zy--FPeQfIhxpGK1yF3UwC1zGGCWgh1Q196xDaLZkLEo9Ur4zx4B-xZs1O2NwZgCNcPB3HnEAuHB',
    'clientSecret' => 'EGoaCn_970xBv9rs9QAPpIHIMzJx7IEwvanKTioxExTzjh33qeoicLo45oGXdAAxHPugcYR28M40W6FL'
							);
		
		//On construit les urls de retour
		
		// output: BackendBeneentrip/web/app_dev.php/....
		$currentPath = $_SERVER['PHP_SELF'];

		// output: localhost
		$hostName = $_SERVER['HTTP_HOST'];

		// output: http://
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
		
		$tab = explode("/",__DIR__);
						
		$nbelts=count($tab);
			
		$rootProject=$tab[$nbelts-4];
		
		if($rootProject!="www")
		$url=$protocol.$hostName.'/'.$rootProject.'/web/app_dev.php/mobapp/payment/execute/paypal';
		else
		$url=$protocol.$hostName.'/web/mobapp/payment/execute/paypal';				
		
		$url_return=$url;
		
		$url_cancel=$url;
		
		$paypal = new PaypalAPI($config);
		
		//$osms->setVerifyPeerSSL(false);
		
		//on recupere l'acces token
		$retour = $paypal->getTokenFromConsumerKey();
		
		//on cree le paiement Paypal avec les donnees du panier
		if (empty($retour['error'])) {
    	
    	$retour=$paypal->createPayment(
        "".$amountTotal,
           $currencyCode,
        "".$amount,
        "".$amountTax,
        "0.00",
        "Reservation BeneenTrip",
        "User_".$idUser,
        "Voyage touristique",
		  "Reservation voyage touristique",
		  "1",
		  "".$amount,
		  "Reservation_".$reservationId->getId(),
	     "Beneentrip",
	     "4thFloor",
	     "unit#34",
		  "Douala",
		  "FR",
		  "95131",
		  "0237679372406",
		  "CA",
	     $url_return,
	     $url_cancel
    );
    
    if (empty($retour['error']))
    {
    //On enregistre le payment dans la BD
	 $payment=new Payment();
	 $payment->setItemId($reservationId->getId());
	 $payment->setTransactionId($retour['id']);
	 $payment->setAmount($amountTotal);
	 $payment->setStatus(strtoupper("UNCOMPLETED"));
	 $payment->setCurrencyCode($currencyCode);
	 $payment->setTransactionToken($paypal->getToken());
	 $payment->setUtilisateur($userId);
	 
	 $em->persist($payment);
	 $em->flush();		
		
		
	 $paypalAnswer['url'] = $retour['links'][1]['href'];			
	 $response = new Response(json_encode($paypalAnswer));
	 
    }
    else
    $response = new Response(json_encode(array('failure'=>$retour['error'])));
     
    	} else {
		$response = new Response(json_encode(array('failure'=>$retour['error'])));
		}
	}
		
		//$response = new Response(json_encode(array()));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;	
      }


      
            public function executerPaiementPaypalAction()
				{
				
				if(count($_GET)>0)
				{
					if(isset($_GET['PayerID']) && isset($_GET['paymentId']) && isset($_GET['token']))
					{
					
					$payerId=$_GET['PayerID'];
					$paymentId=$_GET['paymentId'];
					$token=$_GET['token'];	
					
					$em = $this->getDoctrine()->getManager();
					
					$paymentTransaction = $em->getRepository('BusinessModelBundle:Payment')->myFindByTransactionId($paymentId);
					$reservationId = $em->getRepository('BusinessModelBundle:Reservation')->myFindOne(intval($paymentTransaction->getItemId()));
					
					$config = array(
				    'clientId' => 'AXJ9Zy--FPeQfIhxpGK1yF3UwC1zGGCWgh1Q196xDaLZkLEo9Ur4zx4B-xZs1O2NwZgCNcPB3HnEAuHB',
				    'clientSecret' => 'EGoaCn_970xBv9rs9QAPpIHIMzJx7IEwvanKTioxExTzjh33qeoicLo45oGXdAAxHPugcYR28M40W6FL'
							);
							
					$paypal = new PaypalAPI($config);
		
		
					//on execute le paiement
					$retour = $paypal->executePayment($payerId,$paymentId,$paymentTransaction->getTransactionToken());
					
					//$response = new Response(json_encode($retour));
					
					  if (empty($retour['error']))
    				 {
    				 
    				 //$status=$retour['payer']['status'];
					 //$state=$retour['state'];	
					 
    				 //if (strtoupper($status)=="VERIFIED" && strtoupper($state)=="APPROVED"){
    				 $reservationId->setPaye(true);
    				 $em->flush();
    				 
    				 $paymentTransaction->setTransactionPayer($payerId);
    				 $paymentTransaction->setStatus(strtoupper("COMPLETED"));
    				 $paymentTransaction->setInvoice(
    				 $this->creerFacturePDF(
    				 $this->genererFactureCodeHTML($paymentTransaction->getUtilisateur()->getId(),$paymentTransaction->getItemId()),
    				 $this->genererFactureCodeCSS()));
    				 
    				 $em->flush();			
    				 
    				 //On peut si on veut ici generer la facture PDF de beneentrip et envoyer renvoyer son url
    				 //$paypalBillPDF['url'] = $paymentTransaction->getInvoice();	
    				 //$response = new Response(json_encode($paypalBillPDF));
    				 
    				 			
					 $response = new Response(json_encode(array('success'=>'Transaction paiement bien finalisée.')));
					 //}
					 
					 //else					
    				 //$response = new Response(json_encode(array('failure'=>'Finalisation transaction échouée !!!')));					
    				 
    				 }
    				 else
    				 $response = new Response(json_encode(array('failure'=>$retour['error'])));					
					
					}	
    				else 
    				$response = new Response(json_encode(array('failure'=>'Transaction échouée !!!')));					

				}
				else 
    			$response = new Response(json_encode(array('failure'=>'Transaction échouée !!!')));
				
				
				
				//$response = new Response(json_encode(array()));
				
				//header('Access-Control-Allow-Origin: *'); //allow everybody  
				// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
				$response->headers->set('Access-Control-Allow-Origin', '*');
				
				$response->headers->set('Content-Type', 'application/json');
				
				return $response;	
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
							font-size: 20px;
							}
							
							tr.sousTotalRow{
							background-color:white;
							font-weight: bold;
							text-align: center;
							height: 50px;
							font-size: 15px;
							font-style: italic;
							}
							
							tr.taxeRow{
							background-color:white;
							font-weight: bold;
							text-align: center;
							height: 50px;
							font-size: 15px;
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
						
						
						function genererFactureCodeHTML($idUser, $idReservation)
						{
							
						$em=$this->getDoctrine()->getManager();
						$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
						$reservationId = $em->getRepository('BusinessModelBundle:Reservation')->myFindOne($idReservation);
						
						$nowDate=new \DateTime();
						
						$html='<div class="fond">';
		
						$html=$html.'<h1>'.$this->get('translator')->trans('Facture.mot').' '.strtoupper($userId->getNomComplet()).' N<SUP>o</SUP> '.$nowDate->format('dmYHis').'</h1><br/><br/><br/><br/>'.
						$html=$html.'<h4>'.$this->get('translator')->trans('Facture.debut').' '.$nowDate->format('d-m-Y').'</h4><br/><br/><br/><br/>'.		
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
						
						
						foreach($reservationId->getActivites() as $activite)	{	
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
						$html=$html. (($reservationId->getPaye()) ? $this->get('translator')->trans('Réservation.Ok') : $this->get('translator')->trans('Réservation.nonOk'));
						$html=$html. '</td>'.
						'</tr>';
						}
						
						$html=$html. '<tr class="sousTotalRow">'.
						'<td colspan="4">';
						$html=$html.' '.$this->get('translator')->trans('Facture.sousTotal').' : '.
						'</td>'.
						'<td colspan="2">'.$reservationId->calculerMontantTotal().
						'</td>'.
						'</tr>';
						$html=$html. '<tr class="taxeRow">'.
						'<td colspan="4">';
						$html=$html.' '.$this->get('translator')->trans('Facture.taxe').' : '.
						'</td>'.
						'<td colspan="2">'.$reservationId->calculerMontantTaxe().
						'</td>'.
						'</tr>';
						$html=$html. '<tr class="totalRow">'.
						'<td colspan="4">';
						$html=$html.' '.$this->get('translator')->trans('Facture.total').' : '.
						'</td>'.
						'<td colspan="2">'.$reservationId->calculerMontantTotalAvecTaxe().
						'</td>'.
						'</tr>';
						$html=$html. '</table>'.
						"</div>";
						
						//return htmlspecialchars($html);//cette fonction nous htmlne le brut HTML en remplacant certains caracteres en code HTML: & devient &amp;
						return $html;	
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
						 
						 //on retourne le chemin relatif
						 return 'invoices/'.$nowDate->format('d/m/Y/H').'/'.$nowDate->format('dmYHis').'.pdf';
					}
}
