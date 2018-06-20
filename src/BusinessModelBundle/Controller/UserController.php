<?php

namespace BusinessModelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\User;
use BusinessModelBundle\Form\Type\UserType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



class UserController extends Controller
{
    public function loginAction()
    {
		$request = $this->get('request');
		//on prepare notre reponse vide
		$response=new Response(json_encode(array()));
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		//Les parametres sont recuperer cad le username et le password (champ avec ces names)	
		$username = $request->get('username');
		$password = $request->get('password');
		//Par defaut le retour est a false car on suppose que l'Utilisateur n'a pas ete trouve
		$retour=false;
		//On essaie d'abord de voir si le username est correct		
		$user = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindUsername($username);
		//Si le username est correct on compare ensuite les mots de passe
		if($user!=null){
		$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
		$retour=$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
		}
		
		if($retour){
		$encoders = array(new XmlEncoder(), new JsonEncoder());
		$normalizers = array(new ObjectNormalizer());
		$serializer = new Serializer($normalizers, $encoders);
		$jsonContent = $serializer->serialize($user, 'json');
		//$user = $serializer->deserialize($jsonContent, User::class, 'json');
 		$response = new Response(json_encode($jsonContent));
 		}
 		else 
 		$response = new Response(json_encode(array('failure'=>'Identifiants utilisateur invalides')));
    	
    	$response->headers->set('Content-Type', 'application/json');
		}
		
		return $response;	
		
    }
    
    
    
    public function registerAction()
    {
		$request = $this->get('request');
		//On recupere toutes les donnees du formulaire de name 'businessmodelbundle_user' rempli par l'utilisateur 		
		$registrationArray = $request->get('businessmodelbundle_user');
		$user= new User();
		//Si l'Utilisateur a entre effectivement des donnees on hydrate notre objet User
		if($registrationArray!=null)
		$user->hydrate($registrationArray);
		//$form = $this->createForm('businessmodelbundle_user', $user); 
		//var_dump($request->request->all());
		$response=new Response(json_encode(array()));
		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
		//$form->bind($request);
		//var_dump($user->getEmail());
		//var_dump($form->getErrorsAsString());
		//if($form->isValid()) {
		$retour=false;
		/*$userBDByName = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindUsername($user->getUsername());
		$userBDByEmail = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindEmail($user->getEmail());
		
		if($userBDByName!=null || $userBDByEmail!=null)
		$retour=true;		
		*/
		$userDB=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')
		->myFindUsernameOREmail($user->getUsername(),$user->getEmail());
		
		if($userDB!=null)
		$retour=true;
		
		$encoders = array(new XmlEncoder(), new JsonEncoder());
		$normalizers = array(new ObjectNormalizer());
		$serializer = new Serializer($normalizers, $encoders);
		$jsonContent = $serializer->serialize($user, 'json');		
		
		if($retour){	
		$response = new Response(json_encode(array('failure'=>'Identifiants utilisateur deja existant','User'=>$jsonContent)));		
		}else{ 
		$response = new Response(json_encode(array('success'=>'Identifiants utilisateur Ok','User'=>$jsonContent)));
		}
		$response->headers->set('Content-Type', 'application/json');
		//}
		}    
		  
		return $response;
	 }    
     
}
