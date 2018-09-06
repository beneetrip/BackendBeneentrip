<?php

namespace MobileAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use BusinessModelBundle\Entity\User;
use BusinessModelBundle\Entity\Discussion;
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
		
		//Si on envoit les chaines vides ou nulles on retourne directement l'erreur
		if(!isset($email) || trim($email,'')=="" || !isset($password) || trim($password,'')=="")
		{
			
		$response = new Response(json_encode(array('failure'=>'Données invalides')));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;											
		}
		
		//Par defaut le retour est a false car on suppose que l'Utilisateur n'a pas ete trouve
		$retour=false;
		
		//On essaie d'abord de voir si le username est correct		
		//$user = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindEmail($email);
		
		$user = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindUsernameOREmail($email,$email);
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
			$userfind['firstname'] = $user->getPrenom();
			$userfind['lastname'] = $user->getNom();
			$userfind['username'] = $user->getusername();
			$userfind['birthday'] = $user->getDateNaissance();
			$userfind['kind'] = $user->getGenre();
			$userfind['phone'] = $user->getTelephone();
			$userfind['type'] = $user->getTypeUtilisateur();
			//traitement des langues
			$languesUser = array();

			foreach($user->getLangues() as $langue)
			{
			$row['id'] = $langue->getId();
			$row['nom'] =  $langue->getNom();
			$languesUser[] = $row;
			}	
			$userfind['languages'] = $languesUser;
			
			
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
	
	
	public function languesAction()
    {
		/* $postdata = file_get_contents("php://input");
		$request = json_decode($postdata); */
		
		$result['langues'] = array();
		
		/*$langues=$this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Langue')->myFindAll();
		
		foreach( $langues as $elem){
			
			$result['langues'][] = $elem->getNom();
			
		}*/ 
		
		$em=$this->getDoctrine()->getManager();
		
		$tabLangs=array("German","English","Spanish","French","Chinese","Arabic","Russian");
		
		foreach( $tabLangs as $elem){
			
		$langue=$em->getRepository('BusinessModelBundle:Langue')->myFindByNom($elem);
		
		$row['id'] = $langue->getId();
		$row['nom'] =  $langue->getNom();
		
		$result['langues'][] = $row;
			
		}		
		
					
		
		$response = new Response(json_encode($result));
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');
		
		return $response;
    }
	
	
	
	public function registerAction()
    {
		
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		
		//Si on envoit les chaines vides ou nulles on retourne directement l'erreur
		if(!isset($request->email) || trim($request->email,'')=="" || !isset($request->username) || trim($request->username,'')=="" || 
		!isset($request->lastname) || trim($request->lastname,'')=="" || !isset($request->firstname) || trim($request->firstname,'')=="" ||
		!isset($request->kind) || trim($request->kind,'')=="" || !isset($request->birthday) || trim($request->birthday,'')=="" ||
		!isset($request->type) || trim($request->type,'')=="" || !isset($request->phone) || trim($request->phone,'')=="")
		{
			
		$response = new Response(json_encode(array('failure'=>'Données invalides')));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;											
		}
		
		
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
			
			try{
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
			$user->setDateNaissance(date_create($request->birthday));
			$user->setTypeUtilisateur($request->type);
			$user->setTelephone($request->phone);
			$user->setPrivilege("USER");
			
			$em = $this->getDoctrine()->getManager();
			
			
			//traitement des langues
			$languesUser = array();
			if($request->languages !=null && count($request->languages)>0){
			foreach($request->languages as $langue)
			{
			$langueObj=$em->getRepository('BusinessModelBundle:Langue')->myFindOne($langue);
			$row['id'] = $langueObj->getId();
			$row['nom'] =  $langueObj->getNom();			
			$languesUser[]=$row;	
			$user->addLangue($langueObj);
			}	
			}
			
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
			$userfind['languages'] = $languesUser;
			
			$response = new Response(json_encode($userfind));
			}catch(\Exception $e)
			{
			$response = new Response(json_encode(array('failure'=>'Une erreur s\'est produite, veuillez vérifier vos données')));	
			}
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
		
		//Si on envoit les chaines vides ou nulles on retourne directement l'erreur
		if(!isset($request->email) || trim($request->email,'')=="" || !isset($request->username) || trim($request->username,'')=="" || 
		!isset($request->lastname) || trim($request->lastname,'')=="" || !isset($request->firstname) || trim($request->firstname,'')=="" ||
		!isset($request->kind) || trim($request->kind,'')=="" || !isset($request->birthday) || trim($request->birthday,'')=="" ||
		!isset($request->type) || trim($request->type,'')=="" || !isset($request->phone) || trim($request->phone,'')=="")
		{
			
		$response = new Response(json_encode(array('failure'=>'Données invalides')));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;											
		}
		
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
			      $user->setDateNaissance(date_create($request->birthday));
					$user->setTypeUtilisateur($request->type);
					$user->setTelephone($request->phone);
							
					$em = $this->getDoctrine()->getManager();
					
					//traitement des langues
					foreach($user->getLangues() as $elemLang)
					$user->removeLangue($elemLang);
					
					$languesUser = array();
					if($request->languages !=null && count($request->languages)>0){
					foreach($request->languages as $langue)
					{
					$langueObj=$em->getRepository('BusinessModelBundle:Langue')->myFindOne($langue);
					$row['id'] = $langueObj->getId();
					$row['nom'] =  $langueObj->getNom();			
					$languesUser[]=$row;	
					$user->addLangue($langueObj);
					}	
					}
					
					
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
					$userfind['languages'] = $languesUser;
					
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
 					$user->setDateNaissance(date_create($request->birthday));
					$user->setTypeUtilisateur($request->type);
					$user->setTelephone($request->phone);
					
					$em = $this->getDoctrine()->getManager();
					
					//traitement des langues
					foreach($user->getLangues() as $elemLang)
					$user->removeLangue($elemLang);
					
					$languesUser = array();
					if($request->languages !=null && count($request->languages)>0){
					foreach($request->languages as $langue)
					{
					$langueObj=$em->getRepository('BusinessModelBundle:Langue')->myFindOne($langue);
					$row['id'] = $langueObj->getId();
					$row['nom'] =  $langueObj->getNom();			
					$languesUser[]=$row;	
					$user->addLangue($langueObj);
					}	
					}
					
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
					$userfind['languages'] = $languesUser;
					
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
			      $user->setDateNaissance(date_create($request->birthday));
					$user->setTypeUtilisateur($request->type);
					$user->setTelephone($request->phone);
					
					$em = $this->getDoctrine()->getManager();
					
					
					//traitement des langues
					foreach($user->getLangues() as $elemLang)
					$user->removeLangue($elemLang);
					
					$languesUser = array();
					if($request->languages !=null && count($request->languages)>0){
					foreach($request->languages as $langue)
					{
					$langueObj=$em->getRepository('BusinessModelBundle:Langue')->myFindOne($langue);
					$row['id'] = $langueObj->getId();
					$row['nom'] =  $langueObj->getNom();			
					$languesUser[]=$row;	
					$user->addLangue($langueObj);
					}	
					}
					
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
					$userfind['languages'] = $languesUser;
					
					
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
			$user->setDateNaissance(date_create($request->birthday));
			$user->setTypeUtilisateur($request->type);
			$user->setTelephone($request->phone);
			
			$em = $this->getDoctrine()->getManager();
			
			//traitement des langues
			foreach($user->getLangues() as $elemLang)
			$user->removeLangue($elemLang);
			
			$languesUser = array();
			if($request->languages !=null && count($request->languages)>0){
			foreach($request->languages as $langue)
			{
			$langueObj=$em->getRepository('BusinessModelBundle:Langue')->myFindOne($langue);
			$row['id'] = $langueObj->getId();
			$row['nom'] =  $langueObj->getNom();			
			$languesUser[]=$row;	
			$user->addLangue($langueObj);
			}	
			}
			
			
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
			$userfind['languages'] = $languesUser;
			
			$response = new Response(json_encode($userfind));
			
		}
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
		
    }
    
    
    //Fonction d'optimisation des appels du frontend au niveau du home	
      public function homeAction(){
      
      $postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
       
      $result = array();
      		  
		$result['eclaireurs'] = array();
		$result['destinations'] = array();
		$result['categories'] = array();
		$result['tops'] = array();
		$result['news'] = array();	
		
		$eclaireurs = $em->getRepository('BusinessModelBundle:User')->findBy(array('typeUtilisateur'=>'Guide'), array('id' => 'DESC'));
		
		foreach( $eclaireurs as $elem){
			
			$result['eclaireurs'][] = $elem->getUsername();
		} 
		
		$listDestinations=$em->getRepository('BusinessModelBundle:Activite')->myFindListeDestinations();
		
		foreach( $listDestinations as $elem){
			
			$result['destinations'][] = $elem;
			
		}
		
		$categories=$em->getRepository('BusinessModelBundle:Categorie')->myFindAll();
		
		foreach( $categories as $elem){
			
			$result['categories'][] = $elem->getNom();
			
		}
		
		$idUser=$request->idUser;
		
		//Si on envoit 0 comme id alors c'est un visiteur cad un utilisateur non connecte
		if($idUser==0)
		$result['nbPanier']=0;
		else{
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		
		//On recupere la reservation non payee de l'utilisateur si elle existe deja en BD
		$listeRetour=$em->getRepository('BusinessModelBundle:Reservation')->myFindSurReservations(null,$userId->getNom(),0,null,null);	
		
		//S'il ya pas de reservation non paye de l'utilisateur
		if(count($listeRetour)<=0){
		$result['nbPanier']=0;	
		}
		else 
		{
		$reservationUser=$listeRetour[0];
		$result['nbPanier'] = $reservationUser->compterActivites();
		}
      }
      
      $listeActivites = $em->getRepository('BusinessModelBundle:Activite')->findBy(array(), array('nbVues' => 'DESC'), 4, 0);
		
		//$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->findBy(array(), array('nbVues' => 'DESC'));
		
		foreach($listeActivites as $elem){
			
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
			
			if($elem->getCategorie()!=null)
			$row['categorie'] = $elem->getCategorie()->getNom();
			else 
			$row['categorie'] = null;
			
			$rowImgPrinc['id']=$elem->getImagePrincipale()->getId();
			$rowImgPrinc['url']=$elem->getImagePrincipale()->getUrl();
			$row['image'] = $rowImgPrinc;
			
			//$row['thumb400x350'] = $elem->getImagePrincipale()->linkThumb(400, 350);
			
			//$row['thumb700x620'] = $elem->getImagePrincipale()->linkThumb(700, 620);
			
			$row['images'] = array();
			
			foreach( $elem->getImages() as $imgelem ){
			$rowImg['id'] = $imgelem->getId();
			$rowImg['url'] =  $imgelem->getUrl();
			$row['images'][] = $rowImg;
			}
			
			$result['tops'][] = $row;
		}
		
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->findBy(array(), array('id' => 'DESC'), 4, 0);
		
		//$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->findBy(array(), array('nbVues' => 'DESC'));
		
		foreach($listeActivites as $elem){
			
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
			
			if($elem->getCategorie()!=null)
			$row['categorie'] = $elem->getCategorie()->getNom();
			else 
			$row['categorie'] = null;
			
			$rowImgPrinc['id']=$elem->getImagePrincipale()->getId();
			$rowImgPrinc['url']=$elem->getImagePrincipale()->getUrl();
			$row['image'] = $rowImgPrinc;
			
			//$row['thumb400x350'] = $elem->getImagePrincipale()->linkThumb(400, 350);
			
			//$row['thumb700x620'] = $elem->getImagePrincipale()->linkThumb(700, 620);
			
			$row['images'] = array();
			
			foreach( $elem->getImages() as $imgelem ){
			$rowImg['id'] = $imgelem->getId();
			$rowImg['url'] =  $imgelem->getUrl();
			$row['images'][] = $rowImg;
			}
			
			$result['news'][] = $row;
		}
		
		
		$response = new Response(json_encode($result));
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;	
		}
		
		
		
		//Fonction permettant de reporter un pb sur une activite
		public function reportSurActiviteAction(){
		
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
		/*
		$message = \Swift_Message::newInstance()
        ->setSubject('BeneenTrip Activity Report Problem '.$idUser.''.$idActivite)
        ->setFrom('beneentrip@gmail.com')
        ->setTo('micdejc@gmail.com')
        ->setBody(
            $this->renderView(
                'BusinessModelBundle:Default:report.html.twig',
                array('user' => $userId->getNomComplet(),'activity' => $activiteId->getLibelle(),
			        'phone' => $userId->getTelephone(),'email' => $userId->getEmail()))
			            );
			    $result=$this->get('mailer')->send($message);
				 */
				 $discussion=new Discussion();
				 $discussion->setType("Report");
				 $discussion->setAuteur($userId);
				 $discussion->setActivite($activiteId);
				 
				 $em->persist($discussion);
				 $em->flush();
				 
				 $retour= $this->envoyerMail(
				 "beneentrip@gmail.com",
				 'BeneenTrip Activity Report Problem '.$idUser.''.$idActivite,
				 $this->renderView(
                'BusinessModelBundle:Default:reportActivite.html.twig',
                array('user' => $userId->getNomComplet(),'activity' => $activiteId->getLibelle(),
			        'phone' => $userId->getTelephone(),'email' => $userId->getEmail())
				 )
				 );
				 
				 if(!$retour)
				 $response = new Response(json_encode(array('failure'=>'Échec d\'envoi, le mail n\'est pas parti !!!')));
				 else
				 $response = new Response(json_encode(array('success'=>'Mail envoyé avec succès')));
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;		      
		      
		}
		
		//Fonction permettant de reporter un pb de l'utilisateur
		public function reportAction(){
		
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$idUser=$request->idUser;
		$title=$request->title;
		$message=$request->message;
		
		//Valeurs de tests manuels
		//$idUser=1;
		//$title="Renseignement";
		//$message="Pourquoi les prix des reservations des activites varient avec le temps comme cela ???";
	
		$userId = $em->getRepository('BusinessModelBundle:User')->myFindOne($idUser);
		/*
		$message = \Swift_Message::newInstance()
        ->setSubject('BeneenTrip Activity Report Problem '.$idUser.''.$idActivite)
        ->setFrom('beneentrip@gmail.com')
        ->setTo('micdejc@gmail.com')
        ->setBody(
            $this->renderView(
                'BusinessModelBundle:Default:report.html.twig',
                array('user' => $userId->getNomComplet(),'activity' => $activiteId->getLibelle(),
			        'phone' => $userId->getTelephone(),'email' => $userId->getEmail()))
			            );
			    $result=$this->get('mailer')->send($message);
				 */
				 $discussion=new Discussion();
				 $discussion->setType("Report");
				 $discussion->setAuteur($userId);
				 $discussion->setTitre($title);
				 $discussion->setMessage($message);
				 
				 $em->persist($discussion);
				 $em->flush();
				 
				  $retour= $this->envoyerMail(
				 "beneentrip@gmail.com",
				 "BeneenTrip Report Problem: ".$title,
				 $this->renderView(
                'BusinessModelBundle:Default:report.html.twig',
                array('user' => $userId->getNomComplet(),'message' => $message,
			        'phone' => $userId->getTelephone(),'email' => $userId->getEmail())
				 )
				 );
				 if(!$retour)
				 $response = new Response(json_encode(array('failure'=>'Échec d\'envoi, le mail n\'est pas parti !!!')));
				 else
				 $response = new Response(json_encode(array('success'=>'Mail envoyé avec succès')));
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;		      
		      
		}
		
		
		//Fonction permettant de renitialiser le mot de passe d'un utilisateur
		public function resetPasswordAction()
		{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$em = $this->getDoctrine()->getManager();
		
		$email=$request->email;
		
		
		//Valeurs de tests manuels
		//$email='micdejc@gmail.com';
		
		
		//On verifie si cet email est correspond a un utilisateur dans notre systeme
		$userByEmail = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:User')->myFindEmail($email);
		
		//Si ce n'est pas un utilisateur du systeme on envoit une erreur
		if($userByEmail==null)
		{
	   $response = new Response(json_encode(array('failure'=>'Email utilisateur invalide !!!')));
      
      $response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;	
		}
		
		/*$caracteres = array(2, 'y', 9, 'a', 9, 'j', 5, 'i', 7, 'd', 3, 'b', 2, 's', 6, 'm', 8, 'h', 1, 'e', 9, 't', 2, 'r', 4, 'z',
							6, 'f', 4, 'p', 7, 'n', 1, 's', 'q', 3, 'd', 2, 'v', 0, 'd', 0, 's', 6, 'i', 7, 'm', 9, 'q', 4, 't', 3,
							'q', 2, 'd', 'o', 9, 'd', 'g', 9, 5, 'i', 7, 'v', 3, 2, 6, 's', 8, 1, 'z', 9, 2, 4, 'e', 6, 4, 7, 1, 'v');
        
		$caracteres_aleatoires = array_rand($caracteres, 10); //Généartion d'un code aléatoire pour le mot de passe
		$password = '';
		 
			foreach($caracteres_aleatoires as $i)
			{
				$password .= $caracteres[$i];
			}*/
		
		$password=$this->genererCodeAleatoire(10);	
			
		$salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
      $userByEmail->setSalt($salt);
   
		$encoder = $this->container->get('security.encoder_factory')->getEncoder($userByEmail);
		$encoded = $encoder->encodePassword($password, $userByEmail->getSalt());
		$userByEmail->setPassword($encoded);
		$userByEmail->eraseCredentials();
		
		$em->flush();
		
		 $retour= $this->envoyerMail(
				 $email,
				 "BeneenTrip Password Reset",
				 $this->renderView(
                'BusinessModelBundle:Default:reset.html.twig',
                array('password' => $password)
				 )
				 );
				 if(!$retour)
				 $response = new Response(json_encode(array('failure'=>'Échec d\'envoi, le mail n\'est pas parti !!!')));
				 else
				 $response = new Response(json_encode(array('success'=>'Initialisation mot de passe effectuée avec succès')));
		
		$response->headers->set('Content-Type', 'application/json');  
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');

		return $response;	
		}
		
		//Fonction permettant de generer une mot de passe aleatoire en fonction de la longueur voulue
		function genererCodeAleatoire($longeur) {
		$characts = 'abcdefghijklmnopqrstuvwxyz';
		$characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characts .= '1234567890';
		$code_aleatoire = '';
		
		for($i=0;$i < $longeur;$i++)
		{
		$code_aleatoire .= $characts[ rand() % strlen($characts) ];
		}
		return $code_aleatoire; 	
		}
		
		function envoyerMail($emailParam,$sujetParam,$messageParam)
		{
			
			require_once(__DIR__."/../../../src/AdminBundle/Resources/public/PHPMailer-master/src/PHPMailer.php");
			require_once(__DIR__."/../../../src/AdminBundle/Resources/public/PHPMailer-master/src/SMTP.php");
			require_once(__DIR__."/../../../src/AdminBundle/Resources/public/PHPMailer-master/src/Exception.php");
			
			try{ 
			    $mail = new \PHPMailer\PHPMailer\PHPMailer();
			    $mail->IsSMTP(); // enable SMTP
			
			    //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
			    $mail->SMTPAuth = true; // authentication enabled
			    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			    $mail->Host = "smtp.gmail.com";//smtp gmail pour yahoo c'est : smtp.mail.yahoo.com
			    $mail->Port = 465; // or 587
			    $mail->IsHTML(true);
			    $mail->Username = "beneentrip";
			    $mail->Password = "beneentrip20182019";
			    $mail->SetFrom("beneentrip@gmail.com");
			    $mail->FromName = "BeneenTrip";
			    $mail->Subject =$sujetParam;
			    $mail->Body = $messageParam;
			    $mail->AddAddress("".$emailParam."");
			    
				 //$mail->addAttachment(getCheminDonnees().getFichier($_SESSION['fichier']), "".$Mot['Attach'].""); // Optional name
			    $mail->CharSet = 'UTF-8';//On definit le mail en encodage utf-8 ayant encode au format encode en utf8mb4
			     
			     if(!$mail->Send()) {
			return false;
			     } else {
			return true;
			     }
			     }catch (Exception $e) {
			return false;
			//return $e->getMessage();
			}
		}
}