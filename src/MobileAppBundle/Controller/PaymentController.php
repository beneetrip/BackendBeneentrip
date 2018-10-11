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
use BusinessModelBundle\Entity\StripeAPI;
use BusinessModelBundle\Entity\MonMailer;
use BusinessModelBundle\Entity\PDFInvoice;



class PaymentController extends Controller
{
	
      public function creerPaiementPaypalAction()
      {
      $postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		
		//$idUser=1;
	
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
	    $payment->setReservation($reservationId);
		 $payment->setAmount($amountTotal);
		 $payment->setStatus(strtoupper("COMPLETED"));
		 $payment->setCurrencyCode($currencyCode);
		 $payment->setUtilisateur($userId);
		 
		 $em->persist($payment);
		 $em->flush();	
		 
		 
		 $result=array();
		 $result['success']='Paiement effectué avec succès';
		 
		 
		 //On appelle le service de la gestions de mails avec le payment
		 $result['Mails']= $this->gererMails($payment);
		 
		 //On renvoit la reponse avec success
		$response = new Response(json_encode($result));
		 
		 
		 $response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;	
		}
		
		
		$config = array(
    'clientId' => 'AXJ9Zy--FPeQfIhxpGK1yF3UwC1zGGCWgh1Q196xDaLZkLEo9Ur4zx4B-xZs1O2NwZgCNcPB3HnEAuHB',
    'clientSecret' => 'EGoaCn_970xBv9rs9QAPpIHIMzJx7IEwvanKTioxExTzjh33qeoicLo45oGXdAAxHPugcYR28M40W6FL'
							);
		
		//On construit les urls de retour
		
		$baseURL= MonMailer::baseURL(true);
		
		$url=$baseURL.'mobapp/payment/execute/paypal';				
		
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
	 $payment->setReservation($reservationId);
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
					$reservationId = $em->getRepository('BusinessModelBundle:Reservation')->myFindOne(intval($paymentTransaction->getReservation()->getId()));
					
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
    				 //$paymentTransaction->setInvoice(
    				 //$this->creerFacturePDF(
    				 //$this->genererFactureCodeHTML($paymentTransaction->getUtilisateur()->getId(),$paymentTransaction->getReservation()->getId()),
    				 //$this->genererFactureCodeCSS()));
    				 
    				 $em->flush();			
    				 
    				 //On peut si on veut ici generer la facture PDF de beneentrip et envoyer renvoyer son url
    				 //$paypalBillPDF['url'] = $paymentTransaction->getInvoice();	
    				 //$response = new Response(json_encode($paypalBillPDF));
    				 
    				 			
					 $response = new Response(json_encode(array('success'=>'Transaction paiement bien finalisée.')));
					 
					  $result=array();
						 $result['success']='Transaction paiement bien finalisée.';
						 
						 
						 //On appelle le service de la gestions de mails avec le payment
						 $result['Mails']= $this->gererMails($paymentTransaction);
						 
						 //On renvoit la reponse avec success
						$response = new Response(json_encode($result));
					 
					 //}
					 
					 //else					
    				 //$response = new Response(json_encode(array('failure'=>'Finalisation transaction échouée !!!')));					
    				 
    				 }
    				 else
    				 $response = new Response(json_encode(array('failure'=>$retour['error'])));					
					
					}	
    				else 
    				$response = new Response(json_encode(array('failure'=>'Transaction annulée !!!')));					

				}
				else 
    			$response = new Response(json_encode(array('failure'=>'Transaction annulée !!!')));
				
				
				
				//$response = new Response(json_encode(array()));
				
				//header('Access-Control-Allow-Origin: *'); //allow everybody  
				// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
				$response->headers->set('Access-Control-Allow-Origin', '*');
				
				$response->headers->set('Content-Type', 'application/json');
				
				return $response;	
				}
				
				
				
				
		 //Pour la gestion des mails le payment seul suffit car il contient toutes les infos
		 //Cette methode doit etre la meme chose que la methode du meme nom dans le Controleur par defaut de BusinessModelBundle
		 //Soit faut fusionner alors tous les paiements et ses procedures dans un seul controleur
       public function gererMails(\BusinessModelBundle\Entity\Payment $payment)
       {
       
       $em = $this->getDoctrine()->getManager();
       
      
		 
		 $pathInvoice=PDFInvoice::genererPDFWithTCPDF(PDFInvoice::genererInvoiceCodeHTML($payment->getUtilisateur(),$payment),PDFInvoice::genererCodeCSS(), true);
		 
		 $payment->setInvoice($pathInvoice);
		 $em->flush();
		 
       
       $result=array();
       $erreurs=0;
		 

		 
		 $invoice= $payment->getInvoice();
		 //On doit arreter tous les exceptions et ne pas les laisser nous empecher de terminer l'envoi des mails
		  
		  //On commence par la facture du Touriste...
		  try{
		      $retour= MonMailer::envoyerMail(
				 $payment->getUtilisateur()->getEmail(),
				 "Beneen Trip Invoice",
				 $this->renderView(
                'BusinessModelBundle:Default:invoice.html.twig',
                array('user' => $payment->getUtilisateur()->getNomComplet(), 
                		 'logo' => MonMailer::pathLogo())
				 ),
				$invoice
				 );
				 
				 if(!$retour)
             $erreurs++;
           }catch(\Exception $e){$erreurs++;}
           
            
				//Ensuite on gere les factures des etats des Guides
				$listeGuides= $payment->getReservation()->getListUtilisateurAuteurs();
				
				
		      //On construit notre liste de payments pour les statements des guides		
				$payments=array();	
				$payments[]=$payment;
				
				foreach($listeGuides as $guide){
				try{
				
				$invoice=PDFInvoice::genererPDFWithTCPDF(PDFInvoice::genererStatementCodeHTML($guide,$payments),PDFInvoice::genererCodeCSS(), false);
				$retour= MonMailer::envoyerMail(
				 $guide->getEmail(),
				 "Beneen Trip Statement",
				 $this->renderView(
                'BusinessModelBundle:Default:statement.html.twig',
                array('guide' => $guide->getNomComplet(),
                      'tourist'=> $payment->getUtilisateur()->getNomComplet(), 
                      'phone' => $payment->getUtilisateur()->getTelephone(),
                      'email' => $payment->getUtilisateur()->getEmail(), 
                      'logo' => MonMailer::pathLogo())
				 ),
				 $invoice
				 );
				 
				 if(!$retour)
             $erreurs++;
				}catch(\Exception $e){$erreurs++;}	
				
				}
				
				
				if($erreurs>0)
				$result['Sending']=$this->get('translator')->trans('SentMail.failure');
				else 
				$result['Sending']=$this->get('translator')->trans('SentMail.success');
				
				
				return $result;
				
				} 	
				
				
				
				
				
				
				
}
