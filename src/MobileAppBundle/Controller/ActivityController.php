<?php

namespace MobileAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use BusinessModelBundle\Entity\Image;
use BusinessModelBundle\Entity\Activite;

class ActivityController extends Controller
{
	
    public function indexAction($id)
    {
		
		
    }
	
	public function deleteAction()
    {
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$idactivity = $request->id;
		
		$em = $this->getDoctrine()->getManager();
		
		$activiteId = $em->getRepository('BusinessModelBundle:Activite')->myFindOne($idactivity);
		
		//suppression des images
		foreach( $activiteId->getImages() as $imgelem){
		
			$imgelem->setActivite(null);
			//$activiteId->removeImage($imgelem);
			//$em->remove($imgelem);
		}
			
		if($activiteId != null)
		$em->remove($activiteId);
	
		$em->flush(); 
		
		$result['message'] = "Opération éffectuée avec succèss";
		
		$response = new Response(json_encode($result));
		
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');
		
		return $response;
    }
	
	public function editAction()
    {
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		//$id = $request->photos[1]->id;
		$id = $request->id;
		$em = $this->getDoctrine()->getManager();
		
		$activite = null;
		
		if ($id != 0)
		{
			
			$activiteId = $em->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
			$activiteId->setLibelle($request->libelle);
			$activiteId->setLieuDestination($request->destination);
			$activiteId->setNbParticipants($request->nbParticipant);
			$activiteId->setPrixIndividu($request->prix);
			$activiteId->setDescription($request->description);
			
			$time = $request->heure;
			$date = $request->dateAct;
	
			$activiteId->setDate(\DateTime::createFromFormat("d-m-Y", $date));
			$activiteId->setHeure(\DateTime::createFromFormat("H:i",$time));	
		
			//image principale
			if(($request->photos[0]->id != 0) and ($request->photos[0]->id != $activiteId->getImagePrincipale()->getId()))
			{
				$holdimagePrincipale = $activiteId->getImagePrincipale();
				$imageId = $em->getRepository('BusinessModelBundle:Image')->myFindOne($request->photos[0]->id);
				
				if($imageId != null)
				{
					$activiteId->setImagePrincipale($imageId);
					$em->remove($holdimagePrincipale);
				} 
			}
			
			$updateImage[] = $request->photos[1]->id;
			$updateImage[] = $request->photos[2]->id;
			
			$holdImage = array();
			//on retire les images supprimés
			foreach( $activiteId->getImages() as $imgelem){
			
				if(!(in_array($imgelem->getId(), $updateImage))){
					$activiteId->removeImage($imgelem);
					$em->remove($imgelem);
				}
				else
				{
					$holdImage[] = $imgelem->getId();
				}
			
			}
			
			//on ajoute les nouvelles
			foreach( $updateImage as $imgelem){
			
				if(!(in_array($imgelem, $holdImage)) and $imgelem != 0){
				
					$imageId = $em->getRepository('BusinessModelBundle:Image')->myFindOne($imgelem);
					$activiteId->addImage($imageId);
					$imageId->setActivite($activiteId);
				}
				
			} 
			
			$em->flush();
			
			$activite = $activiteId;
			
		}
		else
		{
			
			$activite = new Activite();
			$activite->setLibelle($request->libelle);
			$activite->setLieuDestination($request->destination);
			$activite->setNbParticipants($request->nbParticipant);
			$activite->setPrixIndividu($request->prix);
			$activite->setDescription($request->description);
			
			$time = $request->heure;
			$date = $request->dateAct;
	
			$activite->setDate(\DateTime::createFromFormat("d-m-Y", $date));
			$activite->setHeure(\DateTime::createFromFormat("H:i",$time));
			
			$user = $em->getRepository('BusinessModelBundle:User')->myFindOne($request->userid);
			$activite->setAuteur($user);
			
			//image principale
			if($request->photos[0]->id != 0)
			{
				$imageId = $em->getRepository('BusinessModelBundle:Image')->myFindOne($request->photos[0]->id);
				
				if($imageId != null)
				{
					$activite->setImagePrincipale($imageId);
				} 
			}
			
			
			$updateImage[] = $request->photos[1]->id;
			$updateImage[] = $request->photos[2]->id;
			
			//on ajoute les nouvelles
			foreach( $updateImage as $imgelem){
			
				if($imgelem != 0){
				
					$imageId = $em->getRepository('BusinessModelBundle:Image')->myFindOne($imgelem);
					$activite->addImage($imageId);
					$imageId->setActivite($activite);
				}
				
			} 
			
			
			$em->persist($activite);
			$em->flush();
			
		}
		
		$result['id'] = $activite->getId();
		
		$response = new Response(json_encode($result));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');
		
		return $response; 
		
    }
	
	
	
	public function showAction($id)
    {
		
		$em = $this->getDoctrine()->getManager();
		
		$activiteId = $em->getRepository('BusinessModelBundle:Activite')->myFindOne($id);
		
		$activiteId->setNbVues( $activiteId->getNbVues() + 1 );
		
		$result['id'] = $activiteId->getId();
		$result['libelle'] = $activiteId->getLibelle();
		$result['description'] = $activiteId->getDescription();
		$result['user']['id'] = $activiteId->getAuteur()->getId();
		$result['user']['username'] = $activiteId->getAuteur()->getUsername();
		$result['user']['photo'] = $activiteId->getAuteur()->getPhoto();
		$result['date'] = $activiteId->getDate()->format('d-m-Y');;
		$result['heure'] = $activiteId->getHeure()->format('H:i');;
		$result['dateclair'] = $activiteId->getDateEnClair();
		$result['nbVues'] = $activiteId->getNbVues();
		$result['prix'] = $activiteId->getPrixIndividu();
		$result['nbParticipants'] = $activiteId->getNbParticipants();
		$result['lieuDestination'] = $activiteId->getLieuDestination(); 
		
		if( $activiteId->getImagePrincipale()!= null )
		{
			$result['image'] = $activiteId->getImagePrincipale()->getUrl();
		}
		else
		{
			$result['image'] = "";
		} 
		
		$result['images'] = array();
		
		//On ajoute aussi l'image principale
		$row['id'] = $activiteId->getImagePrincipale()->getId();
		$row['url'] =  $activiteId->getImagePrincipale()->getUrl();
		$result['images'][] = $row;
		
		foreach( $activiteId->getImages() as $imgelem ){
			$row['id'] = $imgelem->getId();
			$row['url'] =  $imgelem->getUrl();
			$result['images'][] = $row;
		}
		
		$response = new Response(json_encode($result));
		
		$em->flush();
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		return $response; 
		
		
    }
	
	public function listAction($page)
    {
		
		
		
		$result = array();
		
		$nbrResult = 7;
		
		$debutresultat = 0;
		
		
			$debutresultat = $nbrResult * ($page-1);//car le numero de page peut etre negatif
		
		
		$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->findBy(array(), array('id' => 'DESC'), $nbrResult, $debutresultat);
		
		//$listeActivites = $this->getDoctrine()->getManager()->getRepository('BusinessModelBundle:Activite')->findBy(array(), array('id' => 'DESC'));
		
		foreach($listeActivites as $elem){
			
			$row['id'] = $elem->getId();
			$row['libelle'] = $elem->getLibelle();
			$row['description'] = $elem->getDescription();
			$row['user']['id'] = $elem->getAuteur()->getId();
			$row['user']['username'] = $elem->getAuteur()->getUsername();
			$row['user']['photo'] = $elem->getAuteur()->getPhoto();
			$row['dateclair'] = $elem->getDateEnClair();
			$row['nbVues'] = $elem->getNbVues();
			$row['prix'] = $elem->getPrixIndividu();
			$row['nbParticipants'] = $elem->getNbParticipants();
			$row['lieuDestination'] = $elem->getLieuDestination();
			
			$row['image'] = $elem->getImagePrincipale()->getUrl();
			
			/* if( count($elem->getImages()) != 0 )
			{
				$row['image'] = $elem->getImages()[0]->getUrl();
			}
			else
			{
				$row['image'] = "";
			} */
			
			/* foreach($elem->getImages() as $imgelem){
				$imgrow['url'] = $imgelem->getUrl();
				$row['images'] = $imgrow;
			} */
			
			$result[] = $row;
			
		}
		
		$response = new Response(json_encode($result));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		return $response; 
		
    }
	
	public function uploadAction()
    {
		
		//Directory where uploaded images are saved
		 $dirname = "upload_images/galerie/";
		
		
		 // If uploading file
		if($_FILES) {
			
		   //print_r($_FILES);
		   //mkdir ($dirname, 0777, true);
		   $caracteres = array(2, 'y', 9, 'a', 9, 'j', 5, 'i', 7, 'd', 3, 'b', 2, 's', 6, 'm', 8, 'h', 1, 'e', 9, 't', 2, 'r', 4, 'z',
							6, 'f', 4, 'p', 7, 'n', 1, 's', 'q', 3, 'd', 2, 'v', 0, 'd', 0, 's', 6, 'i', 7, 'm', 9, 'q', 4, 't', 3,
							'q', 2, 'd', 'o', 9, 'd', 'g', 9, 5, 'i', 7, 'v', 3, 2, 6, 's', 8, 1, 'z', 9, 2, 4, 'e', 6, 4, 7, 1, 'v');
        
			$caracteres_aleatoires = array_rand($caracteres, 10); //Généartion d'un code aléatoire pour le nom des photos
			$code = '';
		 
			foreach($caracteres_aleatoires as $i)
			{
				$code .= $caracteres[$i];
			}
			
			$new_image_name = $code.".jpg";
			 
			$annee = date('Y');
			$mois = date('m');
			$jour = date('d');
			$heure = date('H');
			$minute = date('i');
			
			$dossier_photos = $dirname. $annee .'/'. $mois .'/'. $jour .'/'. $heure .'/'. $minute;
			
			$nom_image_register = $dirname. $annee .'/'. $mois .'/'. $jour .'/'. $heure .'/'. $minute .'/'. $new_image_name;
			
			if(!is_dir($dossier_photos))
			{
				mkdir($dossier_photos, 0777, true); 
				chmod($dirname. $annee, 0777);
				chmod($dirname. $annee .'/'. $mois, 0777);
				chmod($dirname. $annee .'/'. $mois .'/'. $jour, 0777);
				chmod($dirname. $annee .'/'. $mois .'/'. $jour .'/'. $heure, 0777);
				chmod($dirname. $annee .'/'. $mois .'/'. $jour .'/'. $heure .'/'. $minute, 0777);
			}
			
			move_uploaded_file($_FILES["photo"]["tmp_name"], $dossier_photos."/".$new_image_name);
		   
			$img = new Image();
			$img->setUrl($dossier_photos."/".$new_image_name);
			$img->setAlt($new_image_name);
			$img->setNom($new_image_name);
			$em = $this->getDoctrine()->getManager();
			$em->persist($img);
			$em->flush();
			
			$resp['id'] = $img->getId();
			$resp['url'] = $img->getUrl();
			
			$response = new Response(json_encode($resp));
			
		}
		else
		{
			$response = new Response($dossier_photos."/".$nom_image_register);
		}
		
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		return $response; 
		
    }
	
}
