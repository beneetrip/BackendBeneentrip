<?php

namespace MobileAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
	
    public function loginAction()
    {
	
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		$user['id'] = 56;
		$user['email'] = $request->email;
		$user['password'] = $request->password;
		$user['name'] = "Paul Eric";
		$user['username'] = "paulo";
		$user['age'] = 0;
		$user['kind'] = 0;
		$user['type'] = 0;
		
		$response = new Response(json_encode($user));
		
		/* $request = $this->getRequest();
		$email = $request->request->get('email');*/
		
		//$response = new Response($request->email);
	
		
		
		//header('Access-Control-Allow-Origin: *'); //allow everybody  
		// pour eviter l'erreur ajax : Blocage d’une requête multiorigines (Cross-Origin Request) : la politique « Same Origin » ne permet pas de consulter la ressource distante située Raison : l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
		$response->headers->set('Access-Control-Allow-Origin', '*');
		
		return $response; 
    }
	
	
	public function registerAction()
    {
		
		
    }
	
	public function updateAction()
    {
		
		
		
    }
	
}
