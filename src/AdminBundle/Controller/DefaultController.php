<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
	
    public function indexAction()
    {
       return $this->render('AdminBundle:Default:index.html.twig', array());

    }
	
	public function sidebarmenuAction()
    {
		return $this->render('AdminBundle:Default:sidebarmenu.html.twig', array());
	}
	
	public function formAction()
    {
        return $this->render('AdminBundle:Default:form.html.twig', array());
    }
	
	public function tableAction()
    {
        return $this->render('AdminBundle:Default:table.html.twig', array());
    }
    
    public function languageAction($langue=null)
    {
		if($langue != null)
	    {
	        // On enregistre la langue en session
	        $this->container->get('session')->set('_locale', $langue);
	        $this->container->get('request')->setLocale($langue);
	    }
 
 		 $arrayParam = array('locale' => $this->container->get('request')->getLocale());
 		 //$arrayParam = array('locale' => $this->container->get('session')->get('_locale'));
 		 //print_r($arrayParam);
	    // on tente de rediriger vers la page d'origine
	    $url = $this->container->get('request')->headers->get('referer',$arrayParam);
	    if(empty($url)) {
	        $url = $this->container->get('router')->generate('index',$arrayParam);
	    }
	    //$locale = $this->container->get('request')->getLocale();
	    //var_dump($url);
	    return new RedirectResponse($url);
    }
	
}
