<?php

namespace AdminBundle\EventListener;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class LanguageListener
{
    private $session;

    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * kernel.request event. If a guest user doesn't have an opened session, locale is equal to
     * "undefined" as configured by default in parameters.ini. If so, set as a locale the user's
     * preferred language.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function setLocaleForUnauthenticatedUser(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
        $request = $event->getRequest();
        
        	$locale = $request->getSession()->get('_locale');
			//$locale = $request->getLocale();        	
        	//On recupere l'url car elle contient la langue 
        	//$url=$request->headers->get('referer');
	       //$locale=$request->get('locale');
        	 //var_dump($locale.">>>>>>>>");
            if ($locale != null) {
                $request->setLocale($locale);
                $request->getSession()->set('_locale', $locale);
            } else {
                $request->setLocale($request->getPreferredLanguage(array('fr')));
            }
        
    }

    /**
     * security.interactive_login event. If a user chose a language in preferences, it would be set,
     * if not, a locale that was set by setLocaleForUnauthenticatedUser remains.
     *
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     */
    public function setLocaleForAuthenticatedUser(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($lang = $user->getLanguage()) {
            $this->session->set('_locale', $lang);
        }
    }
}
