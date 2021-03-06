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
		$row['taxe'] = $reservationUser->calculerMontantTaxeActivite($elem);
		$row['image'] =  $elem->getImagePrincipale()->getUrl();
		$result['activites'][] = $row;
		}
		
		$result['montantSousTotal'] = $reservationUser->calculerMontantTotal();
		$result['montantTotal'] = $reservationUser->calculerMontantTotalAvecTaxe();
		$result['montantTaxe'] = $reservationUser->calculerMontantTaxeActiviteTotal();
		//$result['montantTaxe'] = $reservationUser->calculerMontantTaxe();
		
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
		//$idUser=1;
		
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
		$row['taxe'] = $reservationUser->calculerMontantTaxeActivite($elem);
		$row['image'] =  $elem->getImagePrincipale()->getUrl();
		$result['activites'][] = $row;
		}
		
		$result['montantSousTotal'] = $reservationUser->calculerMontantTotal();
		$result['montantTotal'] = $reservationUser->calculerMontantTotalAvecTaxe();
		$result['montantTaxe'] = $reservationUser->calculerMontantTaxeActiviteTotal();
		//$result['montantTaxe'] = $reservationUser->calculerMontantTaxe();
		
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
		
		//Valeur de tests manuels
		//$idUser=1;
		
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		
		
		$result = array();
		$result['Guide']=array();
		$result['Touriste']=array();
		
		//Tableau de nos evenements historiques : 0 pour evenement passe, 1 pour evenement en cours, 2 pour evenement a venir
		$evts=array('0','1','2');
		
		$types=array('0','1');
		
		//Si c'est un Guide on lui renvoit deux types d'historiques
		if(strtoupper($userId->getTypeUtilisateur())=="GUIDE")
		{
		
		foreach($types as $type)
		{
		if($type=='0')
		$nomType="Guide";
		else
		$nomType="Touriste";
		
		foreach($evts as $evt)
		{

		if($evt=='0'){
		$nomEvt="evtPasses";
		$nbEvt="nbEvtPasses";
		}	
		else if($evt=='1'){
		$nomEvt="evtEnCours";
		$nbEvt="nbEvtEnCours";
		}
		else{
		$nomEvt="evtAvenir";
		$nbEvt="nbEvtAvenir";	
		}
		
		
		$listeActivites = $em->getRepository('BusinessModelBundle:Reservation')->myFindHistoriqueSurReservations($userId->getNom(), $type,$evt);
		
		$result[''.$nomType][''.$nbEvt]= count($listeActivites);
		$result[''.$nomType][''.$nomEvt]=array();		
		foreach($listeActivites as $elem){
		$row['id'] = $elem->getId();
		$row['libelle'] = $elem->getLibelle();
		$row['description'] = $elem->getDescriptionEnClair();
		$row['user']['id'] = $elem->getAuteur()->getId();
		$row['user']['username'] = $elem->getAuteur()->getUsername();
		//$row['user']['photo'] = $elem->getAuteur()->getPhoto();
		$row['user']['photo'] = ($elem->getAuteur()->getAvatar() != null) ? $elem->getAuteur()->getAvatar()->getUrl() : null;
		$row['dateclair'] = $elem->getDateEnClair();
		$row['nbVues'] = $elem->getNbVues();
		$row['prix'] = $elem->getPrixIndividu();
		$row['nbParticipants'] = $elem->getNbParticipants();
		$row['lieuDestination'] = $elem->getLieuDestination();
		$row['devise'] = $elem->getDevise();
		$row['duree'] = $elem->getDuree();
			
		$row['image'] = $elem->getImagePrincipale()->getUrl();
			
		//$row['thumb400x350'] = $elem->getImagePrincipale()->linkThumb(400, 350);
			
		//$row['thumb700x620'] = $elem->getImagePrincipale()->linkThumb(700, 620);
		
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations($elem->getLibelle(),$userId->getNom(),'1',null,null);	
		if(count($listeRetour)>0){
		$reservationUser=$listeRetour[0];
		$taxe=$reservationUser->calculerMontantTaxeActivite($elem);
		$row['taxe'] = $taxe;
		$row['montant'] = ($taxe + $elem->getPrixIndividu());
		}
		$row['monnaie'] = "EUR";
		$row['quantite'] = 1;
			
		$result[''.$nomType][''.$nomEvt][] = $row;
		}
		
		}
		
		}	
		
		
		}
		
		
		//Si c'est un touriste on lui renvoit juste un type d'historique
		else {
		
		
		foreach($evts as $evt)
		{
		if($evt=='0'){
		$nomEvt="evtPasses";
		$nbEvt="nbEvtPasses";
		}	
		else if($evt=='1'){
		$nomEvt="evtEnCours";
		$nbEvt="nbEvtEnCours";
		}
		else{
		$nomEvt="evtAvenir";
		$nbEvt="nbEvtAvenir";	
		}
		
		$listeActivites = $em->getRepository('BusinessModelBundle:Reservation')->myFindHistoriqueSurReservations($userId->getNom(), '1',$evt);
		
		$result['Touriste'][''.$nbEvt]= count($listeActivites);
		$result['Touriste'][''.$nomEvt]=array();		
		foreach($listeActivites as $elem){
		$row['id'] = $elem->getId();
		$row['libelle'] = $elem->getLibelle();
		$row['description'] = $elem->getDescriptionEnClair();
		$row['user']['id'] = $elem->getAuteur()->getId();
		$row['user']['username'] = $elem->getAuteur()->getUsername();
		//$row['user']['photo'] = $elem->getAuteur()->getPhoto();
		$row['user']['photo'] = ($elem->getAuteur()->getAvatar() != null) ? $elem->getAuteur()->getAvatar()->getUrl() : null;
		$row['dateclair'] = $elem->getDateEnClair();
		$row['nbVues'] = $elem->getNbVues();
		$row['prix'] = $elem->getPrixIndividu();
		$row['nbParticipants'] = $elem->getNbParticipants();
		$row['lieuDestination'] = $elem->getLieuDestination();
		$row['devise'] = $elem->getDevise();
		$row['duree'] = $elem->getDuree();
			
		$row['image'] = $elem->getImagePrincipale()->getUrl();
			
		//$row['thumb400x350'] = $elem->getImagePrincipale()->linkThumb(400, 350);
			
		//$row['thumb700x620'] = $elem->getImagePrincipale()->linkThumb(700, 620);
		
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations($elem->getLibelle(),$userId->getNom(),'1',null,null);	
		if(count($listeRetour)>0){
		$reservationUser=$listeRetour[0];
		$taxe=$reservationUser->calculerMontantTaxeActivite($elem);
		$row['taxe'] = $taxe;
		$row['montant'] = ($taxe + $elem->getPrixIndividu());
		}
		$row['monnaie'] = "EUR";
		$row['quantite'] = 1;
			
		$result['Touriste'][''.$nomEvt][] = $row;
		}
		
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
