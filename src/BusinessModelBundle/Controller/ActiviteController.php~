<?php

namespace BusinessModelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\Activite;
use BusinessModelBundle\Form\Type\ActiviteType;



class ActiviteController extends Controller
{
    
   
    public function supprimerAction($id)
    {
    	
    	//on prepare notre reponse vide
		$response=new Response(json_encode(array()));
		
		//On surveille les operations de suppressions et en cas d'erreurs on va retourner qu'il y a echec
		try{	
		//On recupere l'Activite correspondant a l'Id
		$activiteId=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		
		//On recupere notre Manager pour la suppression
		$em=$this->getDoctrine()->getManager();
		
			
		$em->remove($activiteId);
		$em->flush();
		$response = new Response(json_encode(array('success'=>'Activite supprimee avec succes')));
		}catch(\Exception $e){
		$response = new Response(json_encode(array('failure'=>'Echec de suppression')));
		}
		
		$response->headers->set('Content-Type', 'application/json');  
		return $response;
	 }
	 
	 public function listeDestinationsAction(){
	
		//on prepare notre reponse vide
		$response=new Response(json_encode(array()));
		
		//On surveille les operations et en cas d'erreurs on va retourner qu'il y a echec
		try{	
		//On recupere l'Activite correspondant a l'Id
		$listDestinations=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->myFindListeDestinations();
		
		$response = new Response(json_encode(array_unique($listDestinations)));
		}catch(\Exception $e){
		$response = new Response(json_encode(array('failure'=>'Echec de requete')));
		}
		
		$response->headers->set('Content-Type', 'application/json');  
		return $response;
	 }		 
	      
     
}
