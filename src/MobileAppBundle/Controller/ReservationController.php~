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
	
	
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		$activiteId = $em->getRepository('BusinessModelBundle:Activite')->myFindOne($idActivite);
		
		//On recupere la reservation non payee de l'utilisateur si elle existe deja en BD
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNomComplet(),false,null,null);
		
		//S'il ya pas de reservation non paye de l'utilisateur
		if(count($listeRetour)<=0){
		$reservationUser = new Reservation();
		$reservationUser->setPaye(false);
		$reservationUser->addUtilisateur($userId);
		$reservationUser->addActivite($activiteId);
		$test=$this->EstBonneReservation($reservationUser,0);
		
		//Si la reservation est bonne
		if($test['resultat']){
		$em->persist($reservationUser);
		$em->flush();
		$result['id'] = $reservationUser->getId();
		$response = new Response(json_encode($result));
		}
		
		//Sinon si la reservation n'est pas bonne
		else
		{
			
			
		if($test["utilisateur"]!=null)
		$response = new Response(json_encode(array(
		'failure'=>'L\'Utilisateur a deja reserve l\'activite',
		'utilisateur'=>$test["utilisateur"]->getNomComplet(),
		'activite'=>$test["activite"]->getLibelle()
		)));
			
		else 	
		$response = new Response(json_encode(array(
		'failure'=>'L\'activite a deja atteint son quota de participation',
		'activite'=>$test["activite"]->getLibelle()
		)));
			
		}
		}
		//Si la reservation existe deja on va verifier que ce n'est pas une activite existante que l'utilisateur veut reserver ou si le quota de l'activite est deja ok
		else
		{
			
		$reservationUser=$listeRetour[0];
		$reservationUser->addActivite($activiteId);
		
		$test=$this->EstBonneReservation($reservationUser,$reservationUser->getId());
		
		//Si la reservation est bonne
		if($test['resultat']){
		$reservationId=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne($reservationUser->getId());
		$reservationId->addActivite($activiteId);
		$em->flush();
		$result['id'] = $reservationId->getId();
		$response = new Response(json_encode($result));
		}
		
		
		//Sinon si la reservation n'est pas bonne
		else
		{
		if($test["utilisateur"]!=null)
		$response = new Response(json_encode(array(
		'failure'=>'L\'Utilisateur a deja reserve l\'activite',
		'utilisateur'=>$test["utilisateur"]->getNomComplet(),
		'activite'=>$test["activite"]->getLibelle()
		)));	
		else 	
		$response = new Response(json_encode(array(
		'failure'=>'L\'activite a deja atteint son quota de participation',
		'activite'=>$test["activite"]->getLibelle()
		)));	
		}
		
		}
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
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNomComplet(),false,null,null);
		
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
		$reservationId=$em->getRepository('BusinessModelBundle:Reservation')->myFindOne($reservationUser->getId());
		$reservationId->removeActivite($activiteId);
		$em->flush();
		$result['id'] = $reservationId->getId();
		$response = new Response(json_encode($result));
		}
		
		}
    }  
    
    	public function showAction()
    	{
    		
    	$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		
		//On recupere la reservation non payee de l'utilisateur si elle existe deja en BD
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNomComplet(),false,null,null);	
		
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
		$row['libelle'] = $elem->getLibelle();
		$row['prix'] =  $elem->getPrixIndividu();
		$result['activites'][] = $row;
		}
		$result['montantTotal'] = $reservationUser->calculerMontantTotal();
		
		$response = new Response(json_encode($result));
		
		}	
		
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
	
	
}
