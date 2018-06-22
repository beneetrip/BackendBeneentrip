<?php

namespace MobileAppBundle\Controller;

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
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		/* $user['id'] = 56;
		$user['email'] = $request->email;
		$user['password'] = $request->password; */
		
		/* $request = $this->get('request');
		//on prepare notre reponse vide
		$response = new Response(json_encode(array())); */
		
		// On vérifie qu'elle est de type POST
		
		$email = $request->email;
		$password = $request->password;
		
		//Par defaut le retour est a false car on suppose que l'Utilisateur n'a pas ete trouve
		$retour=false;
		
		//On essaie d'abord de voir si le username est correct		
		$user = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindEmail($email);
		//Si le mail est correct on compare ensuite les mots de passe
		if($user!=null){
			$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
			$retour=$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
		}
		
		if($retour){
			/* $encoders = array(new XmlEncoder(), new JsonEncoder());
			$normalizers = array(new ObjectNormalizer());
			$serializer = new Serializer($normalizers, $encoders);
			$jsonContent = $serializer->serialize($user, 'json');
			//$user = $serializer->deserialize($jsonContent, User::class, 'json');
			$response = new Response(json_encode($jsonContent)); */
			$userfind['id'] = $user->getId();
			$userfind['email'] =  $user->getEmail();
			$userfind['password'] = $user->getPassword();
			$userfind['name'] = $user->getNomComplet();
			$userfind['username'] = $user->getusername();
			$userfind['age'] = $user->getAge();
			$userfind['kind'] = $user->getGenre();
			$userfind['type'] = $user->getTypeUtilisateur();
			
			$response = new Response(json_encode($userfind));
		}
		else 
			$response = new Response(json_encode(array('failure'=>'Identifiants utilisateur invalides')));
		
		//$response = new Response(json_encode(array()));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;	
		
    }
	
	
	
	
	
	public function registerAction()
    {
		
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		
		$email = $request->email;
		$username = $request->username;
		
		
		//On essaie d'abord de voir si le username est correct		
		$userByEmail = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindEmail($email);
		$userByUsername = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindUsername($username);
		//Si le mail est correct on compare ensuite les mots de passe
		
		if($userByEmail!=null or $userByUsername!=null){
			
			if($userByEmail != null)
			{
				$response = new Response(json_encode(array('failure'=>'Adresse mail déjà utilisée')));
			}
			else
			{
				$response = new Response(json_encode(array('failure'=>'Nom d\'utilisateur déjà utilisé')));
			}

		}
		else
		{
			$user = new User();
			$user->setEmail($request->email);
			
			//encodage mot de passe
        
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $user->setSalt($salt);
      
			$plainPassword = $request->password;
			$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
			$encoded = $encoder->encodePassword($plainPassword, $user->getSalt());
			$user->setPassword($encoded);
			$user->eraseCredentials();
			
			$user->setEnabled(true);
			$user->setNomComplet($request->name);
			$user->setUsername($request->username);
			$user->setGenre($request->kind);
			$user->setAge($request->age);
			$user->setTypeUtilisateur($request->type);
			$user->setPrivilege("USER");
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
		
			$userfind['id'] = $user->getId();
			$userfind['email'] = $user->getEmail();
			$userfind['password'] = $user->getPassword();
			$userfind['name'] = $user->getNomComplet();
			$userfind['username'] =$user->getUsername();
			$userfind['age'] = $user->getAge();
			$userfind['kind'] = $user->getGenre();
			$userfind['type'] = $user->getTypeUtilisateur();
			
			$response = new Response(json_encode($userfind));
		}
			

		//$response = new Response(json_encode(array()));
		
		$response->headers->set('Content-Type', 'application/json');
		
		
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		return $response; 
		
    }
	
	public function updateAction()
    {
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		
		$id = $request->id;
		$email = $request->email;
		$username = $request->username;
		
		
		//On essaie d'abord de voir si le username est correct		
		$userByEmail = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindEmail($email);
		$userByUsername = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindUsername($username);
		
		if($userByEmail!= null or $userByUsername!= null){
			
			
			if( $userByEmail != null and $userByUsername == null)
			{
				
				if($userByEmail->getId() == $id )
				{
					$user = $userByEmail;
					$user->setEmail($request->email);
					$user->setNomComplet($request->name);
					$user->setUsername($request->username);
					$user->setGenre($request->kind);
					$user->setAge($request->age);
					$user->setTypeUtilisateur($request->type);
					
					$em = $this->getDoctrine()->getManager();
					$em->flush();
					
					$userfind['id'] = $user->getId();
					$userfind['email'] = $user->getEmail();
					$userfind['password'] = $user->getPassword();
					$userfind['name'] = $user->getNomComplet();
					$userfind['username'] =$user->getUsername();
					$userfind['age'] = $user->getAge();
					$userfind['kind'] = $user->getGenre();
					$userfind['type'] = $user->getTypeUtilisateur();
					
					$response = new Response(json_encode($userfind));
				}
				else
				{
					$response = new Response(json_encode(array('failure'=>'Adresse mail déjà utilisée')));
				}
				
			}
			
			if($userByUsername != null and $userByEmail == null)
			{
				
				if($userByUsername->getId() == $id)
				{
					$user = $userByEmail;
					$user->setEmail($request->email);
					$user->setNomComplet($request->name);
					$user->setUsername($request->username);
					$user->setGenre($request->kind);
					$user->setAge($request->age);
					$user->setTypeUtilisateur($request->type);
					
					$em = $this->getDoctrine()->getManager();
					$em->flush();
					$userfind['id'] = $user->getId();
					$userfind['email'] = $user->getEmail();
					$userfind['password'] = $user->getPassword();
					$userfind['name'] = $user->getNomComplet();
					$userfind['username'] =$user->getUsername();
					$userfind['age'] = $user->getAge();
					$userfind['kind'] = $user->getGenre();
					$userfind['type'] = $user->getTypeUtilisateur();
					
					$response = new Response(json_encode($userfind));
				}
				else
				{
					$response = new Response(json_encode(array('failure'=>'Nom d\'utilisateur déjà utilisé')));
				}
				
			}
			
			//dans ce cas c'est claire qu'il ya risque de doublon donc on répère juste le doublon
			if($userByUsername != null and $userByEmail != null)
			{
				//cas c'est le meme user qui a l'email et le username mais on met qu'a meme à jour les autres infos
				if($userByUsername->getId()== $id and $userByEmail->getId() == $id) 
				{
					$user = $userByEmail;
					$user->setEmail($request->email);
					$user->setNomComplet($request->name);
					$user->setUsername($request->username);
					$user->setGenre($request->kind);
					$user->setAge($request->age);
					$user->setTypeUtilisateur($request->type);
					
					$em = $this->getDoctrine()->getManager();
					$em->flush();
					$userfind['id'] = $user->getId();
					$userfind['email'] = $user->getEmail();
					$userfind['password'] = $user->getPassword();
					$userfind['name'] = $user->getNomComplet();
					$userfind['username'] =$user->getUsername();
					$userfind['age'] = $user->getAge();
					$userfind['kind'] = $user->getGenre();
					$userfind['type'] = $user->getTypeUtilisateur();
					
					$response = new Response(json_encode($userfind));
				}
				else
				{
					
					if($userByUsername->getId()!= $id)
					{
						$response = new Response(json_encode(array('failure'=>'Nom d\'utilisateur déjà utilisé')));
					}
					
					if($userByEmail->getId()!= $id)
					{
						$response = new Response(json_encode(array('failure'=>'Adresse mail déjà utilisée')));
					}
					
				}
				
			}
			
				
		}
		else
		{
			
			$em = $this->getDoctrine()->getManager();
		
			$user = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->find($id);
			
			$user->setEmail($request->email);
				
			$user->setNomComplet($request->name);
			$user->setUsername($request->username);
			$user->setGenre($request->kind);
			$user->setAge($request->age);
			$user->setTypeUtilisateur($request->type);
			
			$em = $this->getDoctrine()->getManager();
			$em->flush();
			
			$userfind['id'] = $user->getId();
			$userfind['email'] = $user->getEmail();
			$userfind['password'] = $user->getPassword();
			$userfind['name'] = $user->getNomComplet();
			$userfind['username'] =$user->getUsername();
			$userfind['age'] = $user->getAge();
			$userfind['kind'] = $user->getGenre();
			$userfind['type'] = $user->getTypeUtilisateur();
			
			$response = new Response(json_encode($userfind));
			
		}
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
		
    }
	
}
