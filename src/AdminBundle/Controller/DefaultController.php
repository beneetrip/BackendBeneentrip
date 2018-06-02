<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

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
    
	
}
