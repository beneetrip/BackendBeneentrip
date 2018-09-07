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

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BusinessModelBundle:Default:index.html.twig', array('name' => $name));
		
		
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
	   $payment->setItemId($item_no);
	   $payment->setTransactionId($item_transaction);
	   $payment->setAmount($item_price);
	   $payment->setCurrencyCode($item_currency);
	   $payment->setStatus(strtoupper($item_transaction_status));
	   $payment->setUtilisateur($userId);
	   
	   $em->persist($payment);
		$em->flush();
		$response = new Response(json_encode(array('paypalSuccess'=>$this->get('translator')->trans('Réservation.success',array('%elt%' => $elt)))));
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
        
        //Corps de la page des mails de report de problemes sur les activites
        public function reportActiviteAction($user,$activity,$phone,$email)
    {
        return $this->render('BusinessModelBundle:Default:reportActivite.html.twig', array('user' => $user,'activity' => $activity,
        'phone' => $phone,'email' => $email));
		
    }


  	//Corps de la page des mails de report de problemes sur les activites
        public function reportAction($user,$message,$phone,$email)
    {
        return $this->render('BusinessModelBundle:Default:report.html.twig', array('user' => $user,'message' => $message,
        'phone' => $phone,'email' => $email));
		
    }
    
    //Corps de la page des mails de reinitialisation des mots de passes
        public function resetAction($password)
    {
        return $this->render('BusinessModelBundle:Default:reset.html.twig', array('password' => $password));
		
    } 
}
