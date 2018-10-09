<?php

namespace BusinessModelBundle\Entity;

class MonMailer
{
    public function __construct()
    {
    }
    
    function envoyerMail($emailParam, $sujetParam, $messageParam, $fichierParam)
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
			    $mail->FromName = "Beneen Trip";
			    $mail->Subject =$sujetParam;
			    $mail->Body = $messageParam;
			    $mail->AddAddress("".$emailParam."");
			    
				 if(isset($fichierParam) && trim($fichierParam,'')!=""){
				 
				 //on recupere le nom du fichier
				 $tab = explode("/",$fichierParam);	
	          $nomfichier= array_pop($tab);
	          
	          //Si le chemin du fichier commence par invoices on met le prefix invoice_ sur le fichier attache au mail sinon c'est statement_
				if (strpos($fichierParam, 'invoices') === 0)
				$prefix="Invoice_";
				else
				$prefix="Statement_";

	          
	          $mail->addAttachment(__DIR__.'/../../../web/'.$fichierParam, $prefix."".$nomfichier); // Optional name
				 }

				 
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
		
		
		//Cette fonction permet de nous renvoyer l'URL du logo Beneen Trip a inclure dans le mail lors des envois
		static function pathLogo(){
      //On construit les urls de retour
		
		// output: BackendBeneentrip/web/app_dev.php/....
		$currentPath = $_SERVER['PHP_SELF'];

		// output: localhost
		$hostName = $_SERVER['HTTP_HOST'];

		// output: http://
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
		
		$tab = explode("/",__DIR__);
						
		$nbelts=count($tab);
			
		$rootProject=$tab[$nbelts-4];
		
		if($rootProject!="www")
		$logo=$protocol.$hostName.'/'.$rootProject.'/web/bundles/admin/img/logoBeneenTrip.png';
		else
		$logo=$protocol.$hostName.'/web/bundles/admin/img/logoBeneenTrip.png';	
		
		return $logo;
    }
}
