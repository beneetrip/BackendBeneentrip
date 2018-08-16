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

class CommandController extends Controller
{
	
   
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
			$user->setNom($request->lastname);
			$user->setPrenom($request->firstname);
			$user->setUsername($request->username);
			$user->setGenre($request->kind);
			$user->setDateNaissance($request->birthday);
			$user->setTypeUtilisateur($request->type);
			$user->setTelephone($request->phone);
			$user->setPrivilege("USER");
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
		
			$userfind['id'] = $user->getId();
			$userfind['email'] =  $user->getEmail();
			$userfind['password'] = $user->getPassword();
			$userfind['firstname'] = $user->getPrenom();
			$userfind['lastname'] = $user->getNom();
			$userfind['username'] = $user->getusername();
			$userfind['birthday'] = $user->getDateNaissance();
			$userfind['kind'] = $user->getGenre();
			$userfind['phone'] = $user->getTelephone();
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
					$user->setNom($request->lastname);
					$user->setPrenom($request->firstname);
					$user->setUsername($request->username);
					$user->setGenre($request->kind);
					$user->setDateNaissance($request->birthday);
					$user->setTypeUtilisateur($request->type);
					$user->setTelephone($request->phone);
							
					$em = $this->getDoctrine()->getManager();
					$em->flush();
					
					$userfind['id'] = $user->getId();
					$userfind['email'] =  $user->getEmail();
					$userfind['password'] = $user->getPassword();
					$userfind['firstname'] = $user->getPrenom();
					$userfind['lastname'] = $user->getNom();
					$userfind['username'] = $user->getusername();
					$userfind['birthday'] = $user->getDateNaissance();
					$userfind['kind'] = $user->getGenre();
					$userfind['phone'] = $user->getTelephone();
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
					$user = $userByUsername;
					$user->setNom($request->lastname);
					$user->setPrenom($request->firstname);
					$user->setUsername($request->username);
					$user->setGenre($request->kind);
					$user->setDateNaissance($request->birthday);
					$user->setTypeUtilisateur($request->type);
					$user->setTelephone($request->phone);
					
					$em = $this->getDoctrine()->getManager();
					$em->flush();
					$userfind['id'] = $user->getId();
					$userfind['email'] =  $user->getEmail();
					$userfind['password'] = $user->getPassword();
					$userfind['firstname'] = $user->getPrenom();
					$userfind['lastname'] = $user->getNom();
					$userfind['username'] = $user->getusername();
					$userfind['birthday'] = $user->getDateNaissance();
					$userfind['kind'] = $user->getGenre();
					$userfind['phone'] = $user->getTelephone();
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
					$user->setNom($request->lastname);
					$user->setPrenom($request->firstname);
					$user->setUsername($request->username);
					$user->setGenre($request->kind);
					$user->setDateNaissance($request->birthday);
					$user->setTypeUtilisateur($request->type);
					$user->setTelephone($request->phone);
					
					$em = $this->getDoctrine()->getManager();
					$em->flush();
					$userfind['id'] = $user->getId();
					$userfind['email'] =  $user->getEmail();
					$userfind['password'] = $user->getPassword();
					$userfind['firstname'] = $user->getPrenom();
					$userfind['lastname'] = $user->getNom();
					$userfind['username'] = $user->getusername();
					$userfind['birthday'] = $user->getDateNaissance();
					$userfind['kind'] = $user->getGenre();
					$userfind['phone'] = $user->getTelephone();
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
				
			$user->setNom($request->lastname);
			$user->setPrenom($request->firstname);
			$user->setUsername($request->username);
			$user->setGenre($request->kind);
			$user->setDateNaissance($request->birthday);
			$user->setTypeUtilisateur($request->type);
			$user->setTelephone($request->phone);
			
			$em = $this->getDoctrine()->getManager();
			$em->flush();
			
			$userfind['id'] = $user->getId();
			$userfind['email'] =  $user->getEmail();
			$userfind['password'] = $user->getPassword();
			$userfind['firstname'] = $user->getPrenom();
			$userfind['lastname'] = $user->getNom();
			$userfind['username'] = $user->getusername();
			$userfind['birthday'] = $user->getDateNaissance();
			$userfind['kind'] = $user->getGenre();
			$userfind['phone'] = $user->getTelephone();
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
