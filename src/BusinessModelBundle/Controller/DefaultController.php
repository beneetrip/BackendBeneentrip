<?php

namespace BusinessModelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use BusinessModelBundle\Entity\Reservation;
use BusinessModelBundle\Entity\Payment;
use BusinessModelBundle\Entity\StripeAPI;
use BusinessModelBundle\Entity\MonMailer;
use BusinessModelBundle\Entity\PDFInvoice;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BusinessModelBundle:Default:index.html.twig', array('name' => $name));
		
		
    }
    
    
     //Cette methode permet de valider les paiements gratuits cad ceux dont la somme est nulle;
     public function validerPaiementAction(){
     
      $elt=$this->get('translator')->trans('Barre.Réservation.Mot');  
		$response = new Response(json_encode(array('ValidationFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
      
     	
     if(isset($_POST) && count($_POST)>0){
      
      $item_price  = $_POST['amount'];
		$item_no  = $_POST['item_number'];
		$item_currency  = $_POST['currency_code'];
		$id_user  = $_POST['custom'];
		
		$em = $this->getDoctrine()->getManager();
		$reservationId=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne(intval($item_no));
		$userId=$em->getRepository('BusinessModelBundle:User')->myFindOne(intval($id_user));
		
		
		//On teste si la somme totale est nulle car ainsi pas besoin d'aller chez stripe on fait les mise a jour et on renvoit immediatement la reponse
		if(floatval($item_price)==0.0)
		{
		$reservationId->setPaye(true);
		$em->flush();
			
		//On enregistre le payment dans la BD et on met a jour la reservation de l'utilisateur: elle est reglee
		 $payment=new Payment();
	    $payment->setReservation($reservationId);
		 $payment->setAmount($item_price);
		 $payment->setStatus(strtoupper("COMPLETED"));
		 $payment->setCurrencyCode($item_currency);
		 $payment->setUtilisateur($userId);
		 $em->persist($payment);
		 $em->flush();	
		 
		 $result=array();
		 $result['ValidationSuccess']=$this->get('translator')->trans('Réservation.success',array('%elt%' => $elt));
		 
		 
		 //On appelle le service de la gestions de mails avec le payment
		 $result['Mails']= $this->gererMails($payment);
		 
		 //On renvoit la reponse avec success
		$response = new Response(json_encode($result));
		}else {
		$response = new Response(json_encode(array('ValidationFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
		}
		
      }else {
		$response = new Response(json_encode(array('ValidationFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
		}  
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;   
          
     }
    
    
    
      public function finalisePaiementAction(){
       
      $elt=$this->get('translator')->trans('Barre.Réservation.Mot');  
		$response = new Response(json_encode(array('paypalFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
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
		//On controle les donnees envoyees par Paypal en cas de transaction ok on met a jour notre reservation en mettant etat paye a true
		if($reservationId!=null && $item_price>=$reservationId->calculerMontantTotal() && $item_currency=="EUR" && strtoupper($item_transaction_status)=="COMPLETED"){
		
		$reservationId->setPaye(true);
		
		$payment=new Payment();
	   $payment->setReservation($reservationId);
	   $payment->setTransactionId($item_transaction);
	   $payment->setAmount($item_price);
	   $payment->setCurrencyCode($item_currency);
	   $payment->setStatus(strtoupper($item_transaction_status));
	   $payment->setUtilisateur($userId);
	   
	   $em->persist($payment);
		$em->flush();
		
		$result=array();
		 $result['paypalSuccess']=$this->get('translator')->trans('Réservation.success',array('%elt%' => $elt));
		 
		 
		 //On appelle le service de la gestions de mails avec le payment
		 $result['Mails']= $this->gererMails($payment);
		 
		 //On renvoit la reponse avec success
		$response = new Response(json_encode($result));
		
		}else
		$response = new Response(json_encode(array('paypalFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
		}
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;
        }
        
        
        public function finalisePaiementStripeAction(){
       
      $elt=$this->get('translator')->trans('Barre.Réservation.Mot');  
		$response = new Response(json_encode(array('stripeFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
       //Comme il est possible qu'on nous retourne apres un transaction d'achat sur Stripe, on va donc tester notre transaction si elle a bien eu lieu
		if(isset($_POST) && count($_POST)>0){
		
		$config = array(
    'clientSecret' => 'sk_test_Bdotxvl2laqMFYMQTRIDQbun'
		);
		
		$stripe = new StripeAPI($config);
		
		$token  = $_POST['stripeToken'];
		$email  = $_POST['stripeEmail'];
		$item_price  = $_POST['amount'];
		$item_no  = $_POST['item_number'];
		$item_currency  = $_POST['currency_code'];
		$id_user  = $_POST['custom'];
		
		
		$em = $this->getDoctrine()->getManager();
		$reservationId=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne(intval($item_no));
		$userId=$em->getRepository('BusinessModelBundle:User')->myFindOne(intval($id_user));
		
		$retour = $stripe->createCustomer($email, $token);

		if (empty($retour['error'])) {
			
			//echo "<br>------------------------------------------------------------------------<br>";
		    //print_r($response);
		    //echo "<br>------------------------------------------------------------------------<br>";
		    
			
		    $customerId = $retour['id'];
		    
		    
		    $retour=$stripe->createCharge($item_price, $item_currency, $customerId);
		    
		    //echo "<br>------------------------------------------------------------------------<br>";
		    //print_r($response);
		    //echo "<br>------------------------------------------------------------------------<br>";
		    
		    
		    $item_transaction = $retour['balance_transaction'];
		
			//echo 'Item No: '.$item_no."<br/>";
			//echo 'UserId: '.$userId."<br/>";
			//echo 'Transaction Id: '.$item_transaction."<br/>";
		
			//echo  $response['outcome']['seller_message']."<br/>";
			//echo  $response['status']."<br/>";
			
			$item_transaction_status= $retour['status'];
			
			$outcome=$retour['outcome']['seller_message'];
		
		//On controle les donnees envoyees par Stripe en cas de transaction ok on met a jour notre reservation en mettant etat paye a true
		if($reservationId!=null && $item_price>=($reservationId->calculerMontantTotal()*100) && $item_currency=="EUR" 
		&& strtoupper($item_transaction_status)=="SUCCEEDED" && strtoupper($outcome)=="PAYMENT COMPLETE."){
		
		$reservationId->setPaye(true);
		
		$payment=new Payment();
	   $payment->setReservation($reservationId);
	   $payment->setTransactionId($item_transaction);
	   $payment->setAmount($reservationId->calculerMontantTotal());
	   $payment->setCurrencyCode($item_currency);
	   $payment->setStatus(strtoupper('COMPLETED'));
	   $payment->setUtilisateur($userId);
	      
	   $em->persist($payment);
		$em->flush();
		
		
		 $result=array();
		 $result['stripeSuccess']=$this->get('translator')->trans('Réservation.success',array('%elt%' => $elt));
		 
		 
		 //On appelle le service de la gestions de mails avec le payment
		 $result['Mails']= $this->gererMails($payment);
		 
		 //On renvoit la reponse avec success
		$response = new Response(json_encode($result));
		
		}else
		$response = new Response(json_encode(array('stripeFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
		}
		
		} else {
		$response = new Response(json_encode(array('stripeFailure'=>$this->get('translator')->trans('Réservation.failure',array('%elt%' => $elt)))));
		}
					
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;
        }
        
        //Corps de la page des mails de report de problemes sur les activites
        public function reportActiviteAction($user,$activity,$phone,$email)
    {
        return $this->render('BusinessModelBundle:Default:reportActivite.html.twig', array('user' => $user,'activity' => $activity,
        'phone' => $phone,'email' => $email, 'logo' => MonMailer::pathLogo()));
		
    }


  	//Corps de la page des mails de report de problemes sur les activites
        public function reportAction($user,$message,$phone,$email)
    {
        return $this->render('BusinessModelBundle:Default:report.html.twig', array('user' => $user,'message' => $message,
        'phone' => $phone,'email' => $email, 'logo' => MonMailer::pathLogo()));
		
    }
    
    //Corps de la page des mails de reinitialisation des mots de passes
        public function resetAction($user,$password)
    {
        return $this->render('BusinessModelBundle:Default:reset.html.twig', array('user' => $user,'password' => $password, 'logo' => MonMailer::pathLogo()));
		
    } 
    
    //Corps de la page des mails de l'envoi des factures touriste
        public function invoiceAction($user)
    {
        return $this->render('BusinessModelBundle:Default:invoice.html.twig', array('user' => $user, 'logo' => MonMailer::pathLogo()));
		
    }
    
    //Corps de la page des mails de l'envoi des etats guide
        public function statementAction($guide, $tourist, $phone, $email)
    {
        return $this->render('BusinessModelBundle:Default:statement.html.twig', array('guide' => $guide,
        'tourist' => $tourist, 'phone' => $phone, 'email' => $email, 'logo' => MonMailer::pathLogo()));
		
    }
   			
   			
   			
   			
   							
		 //Pour la gestion des mails le payment seul suffit car il contient toutes les infos 
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
