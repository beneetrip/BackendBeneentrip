<?php

namespace MobileAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Reservation;
use BusinessModelBundle\Entity\User;
use BusinessModelBundle\Entity\Activite;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ReservationController extends Controller
{
	
   public function editPanierAction()
    {
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		$idActivite=$request->idActivite;
		
		//Valeurs de tests manuels
		//$idUser=2;
		//$idActivite=2;
	
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		$activiteId = $em->getRepository('BusinessModelBundle:Activite')->myFindOne($idActivite);
		
		//On recupere la reservation non payee de l'utilisateur si elle existe deja en BD
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNom(),'0',null,null);
		
		//S'il ya pas de reservation non paye de l'utilisateur
		if(count($listeRetour)<=0){
			
		$nbDejaReserve=$em->getRepository('BusinessModelBundle:Reservation')->nombreReservationsDeja(null,$activiteId->getId(),$userId->getId());
		
		$quota=$em->getRepository('BusinessModelBundle:Reservation')->nombreReservationsDeja(null,$activiteId->getId(),null);	
		
		//on teste si l'activite ne se trouve pas deja dans une reservation de l'utilisateur
		if($nbDejaReserve>0)
		$response = new Response(json_encode(array(
		'failure'=>'L\'Utilisateur a deja reserve l\'activite',
		'utilisateur'=>$userId->getNomComplet(),
		'activite'=>$activiteId->getLibelle()
		)));
		//on teste si le quota de l'activite n'est pas deja atteint
		else if($quota>=$activiteId->getNbParticipants())
		$response = new Response(json_encode(array(
		'failure'=>'L\'activite a deja atteint son quota de participation',
		'activite'=>$activiteId->getLibelle()
		)));
		else{
		$reservationUser = new Reservation();
		$reservationUser->setPaye(false);
		$reservationUser->addUtilisateur($userId);
		$reservationUser->addActivite($activiteId);
		$em->persist($reservationUser);
		$em->flush();
		$result['id'] = $reservationUser->getId();
		$response = new Response(json_encode($result));
		}
		
		}
		//Si la reservation existe deja on va verifier que ce n'est pas une activite existante que l'utilisateur veut reserver ou si le quota de l'activite est deja ok
		else
		{
			
		$reservationUser=$listeRetour[0];
		
		$quota=$em->getRepository('BusinessModelBundle:Reservation')->nombreReservationsDeja(null,$activiteId->getId(),null);
		//on teste si l'activite ne se trouve pas deja dans la reservation
		if($reservationUser->estDansReservation($activiteId))
		$response = new Response(json_encode(array(
		'failure'=>'L\'Utilisateur a deja reserve l\'activite',
		'utilisateur'=>$userId->getNomComplet(),
		'activite'=>$activiteId->getLibelle()
		)));
		//on teste si le quota de l'activite n'est pas deja atteint
		else if($quota>=$activiteId->getNbParticipants())
		$response = new Response(json_encode(array(
		'failure'=>'L\'activite a deja atteint son quota de participation',
		'activite'=>$activiteId->getLibelle()
		)));
		else{
		$reservationUser->addActivite($activiteId);
		$em->flush();
		$result['id'] = $reservationUser->getId();
		$response = new Response(json_encode($result));
		}	
		
		}
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;
    }
    
		
	public function deletePanierAction()
    {
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		$idActivite=$request->idActivite;
	
	
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		$activiteId = $em->getRepository('BusinessModelBundle:Activite')->myFindOne($idActivite);
		
		//On recupere la reservation non payee de l'utilisateur si elle existe deja en BD
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNom(),'0',null,null);
		
		//S'il ya pas de reservation non paye de l'utilisateur
		if(count($listeRetour)<=0){
			
		$response = new Response(json_encode(array(
		'failure'=>'Le panier n\'existe pas'
		)));	
		}
		
		//Si la reservation existe deja on va verifier que ce n'est pas une activite existante que l'utilisateur veut reserver ou si le quota de l'activite est deja ok
		else
		{
		$reservationUser=$listeRetour[0];
		
		//si l'activite n'est pas dans la reservation
		if(!$reservationUser->estDansReservation($activiteId)){
		$response = new Response(json_encode(array(
		'failure'=>'L\'activite n\'existe pas dans le panier',
		'activite'=>$activiteId->getLibelle()
		)));
		}
		
		//Sinon L'activite est dans la reservation
		else{
		$reservationUser->removeActivite($activiteId);
		$em->flush();
		//$result['id'] = $reservationUser->getId();
		//$response = new Response(json_encode($result));
		$result['activites'] = array();
		
		foreach( $reservationUser->getActivites() as $elem ){
		$row['id'] = $elem->getId();
		$row['libelle'] = $elem->getLibelle();
		$row['prix'] =  $elem->getPrixIndividu();
		$row['image'] =  $elem->getImagePrincipale()->getUrl();
		$result['activites'][] = $row;
		}
		
		$result['montantSousTotal'] = $reservationUser->calculerMontantTotal();
		$result['montantTotal'] = $reservationUser->calculerMontantTotalAvecTaxe();
		$result['montantTaxe'] = $reservationUser->calculerMontantTaxe();
		
		$response = new Response(json_encode($result));
		}
		
		}
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;
    }  
    
    	public function showAction()
    	{
    		
    	$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		
		//Valeur de tests manuels
		//$idUser=2;
		
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		
		//On recupere la reservation non payee de l'utilisateur si elle existe deja en BD
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNom(),'0',null,null);	
		
		//S'il ya pas de reservation non paye de l'utilisateur
		if(count($listeRetour)<=0){
			
		$response = new Response(json_encode(array(
		'failure'=>'Le panier n\'existe pas'
		)));	
		}
		else 
		{
		$reservationUser=$listeRetour[0];
		
		$result['activites'] = array();
		
		foreach( $reservationUser->getActivites() as $elem ){
		$row['id'] = $elem->getId();
		$row['libelle'] = $elem->getLibelle();
		$row['prix'] =  $elem->getPrixIndividu();
		$row['image'] =  $elem->getImagePrincipale()->getUrl();
		$result['activites'][] = $row;
		}
		
		$result['montantSousTotal'] = $reservationUser->calculerMontantTotal();
		$result['montantTotal'] = $reservationUser->calculerMontantTotalAvecTaxe();
		$result['montantTaxe'] = $reservationUser->calculerMontantTaxe();
		
		$response = new Response(json_encode($result));
		
		}
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;	
		
    	} 
    	
    	public function nombreAction()
    	{
    		
    	$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		
		//Valeur de tests manuels
		//$idUser=2;
		
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		
		//On recupere la reservation non payee de l'utilisateur si elle existe deja en BD
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNom(),'0',null,null);	
		
		//S'il ya pas de reservation non paye de l'utilisateur
		if(count($listeRetour)<=0){
			
		$response = new Response(json_encode(array(
		'failure'=>'Le panier n\'existe pas'
		)));	
		}
		else 
		{
		$reservationUser=$listeRetour[0];
		
		$result['nombre'] = $reservationUser->compterActivites();
		
		$response = new Response(json_encode($result));
		
		}
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;	
		
    	}
    	
    	public function activitiesHistoryAction()
    	{
    	$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		
		$typeHist=$request->type;
		
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		
		$dateNow=new \Datetime();
		
		//on n'oublie pas de bien formatter nos dates pour les requetes	
		$dateString=$dateNow->format('Y-m-d');
		
		$heureString=$dateNow->format('H:i');
		
		$result = array();
		
		//si type ==-1 ce sont les historiques du passe
		if($typeHist=="-1")
    	$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindHistoriqueSurReservations
		($userId->getNom(), null, $dateString, null, $heureString);
		//si type ==0 ce sont les historiques en cours
		else if($typeHist=="0")
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindHistoriqueSurReservations
		($userId->getNom(), $dateString, $dateString, null, null);
		//sinon ce sont les historiques a venir
		else
		$listeReservations = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Reservation')->myFindHistoriqueSurReservations
		($userId->getNom(), $dateString, null, $heureString, null);
		
		foreach($listeReservations as $reservation){
		foreach($reservation->getActivites() as $activite){
		$row['id'] = $elem->getId();
		$row['libelle'] = $elem->getLibelle();
		$row['description'] = $elem->getDescriptionEnClair();
		$row['user']['id'] = $elem->getAuteur()->getId();
		$row['user']['username'] = $elem->getAuteur()->getUsername();
		$row['user']['photo'] = $elem->getAuteur()->getPhoto();
		$row['dateclair'] = $elem->getDateEnClair();
		$row['nbVues'] = $elem->getNbVues();
		$row['prix'] = $elem->getPrixIndividu();
		$row['nbParticipants'] = $elem->getNbParticipants();
		$row['lieuDestination'] = $elem->getLieuDestination();
			
		$row['image'] = $elem->getImagePrincipale()->getUrl();
			
		$row['thumb400x350'] = $elem->getImagePrincipale()->linkThumb(400, 350);
			
		$row['thumb700x620'] = $elem->getImagePrincipale()->linkThumb(700, 620);
			
		$result[] = $row;
		}		
		}
		
		$response = new Response(json_encode($result));		
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;		
    	} 
    
	
	
}
