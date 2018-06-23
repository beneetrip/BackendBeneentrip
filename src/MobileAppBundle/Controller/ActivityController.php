<?php

namespace MobileAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
	
    public function indexAction($id)
    {
		
    }
	
	public function addAction()
    {
		
		$result = array(2,5,7);
		
		$response = new Response(json_encode($_POST));
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
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
		$result['dateclair'] = $activiteId->getDateEnClair();
		$result['nbVues'] = $activiteId->getNbVues();
		$result['prix'] = $activiteId->getPrixIndividu();
		$result['nbParticipants'] = $activiteId->getNbParticipants();
		$result['lieuDestination'] = $activiteId->getLieuDestination();
		
		if(count($activiteId->getImages()) != 0 )
		{
			$result['image'] = $activiteId->getImages()[0]->getUrl();
		}
		else
		{
			$result['image'] = "";
		} 
		
		$result['images'] = array();
		
		foreach( $activiteId->getImages() as $imgelem ){
			$result['images'][] =  $imgelem->getUrl();
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
			
			$row['image'] = $elem->getImages()[0]->getUrl();
			
			if( count($elem->getImages()) != 0 )
			{
				$row['image'] = $elem->getImages()[0]->getUrl();
			}
			else
			{
				$row['image'] = "";
			}
			
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
		 $dirname = "photos"; 
		
		 // If uploading file
		 if ($_FILES) {
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
			
			$dossier_photos = 'upload_images/galerie/'. $annee .'/'. $mois .'/'. $jour .'/'. $heure .'/'. $minute;
			
			$nom_image_register = 'upload_images/galerie/'. $annee .'/'. $mois .'/'. $jour .'/'. $heure .'/'. $minute .'/'. $new_image_name;
			
			if(!is_dir($dossier_photos))
			{
				mkdir($dossier_photos, 0777, true); 
				chmod('images/photos/'. $annee, 0777);
				chmod('images/photos/'. $annee .'/'. $mois, 0777);
				chmod('images/photos/'. $annee .'/'. $mois .'/'. $jour, 0777);
				chmod('images/photos/'. $annee .'/'. $mois .'/'. $jour .'/'. $heure, 0777);
				chmod('images/photos/'. $annee .'/'. $mois .'/'. $jour .'/'. $heure .'/'. $minute, 0777);
			}
			
		  
		   
		   move_uploaded_file($_FILES["photo"]["tmp_name"], $dossier_photos."/".$new_image_name);
		   
		 }
		
		

		$response = new Response($nom_image_register);
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		return $response; 
		
    }
	
}
