<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Listener;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use KMJ\ToolkitBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Listens for a page load and checks the database to determine if a user needs to have
 * thier password reset
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @Service()
 * @Tag("kernel.event_subscriber", attributes={"event" = "kernel.request", "method" = "isPasswordReset"})
 */
class PasswordResetListener implements EventSubscriberInterface {

    /**
     * Route name to be used when redirecting to change password page
     */
    const CHANGE_PASSWORD_ROUTE = "change_password";

    /**
     * The security component
     * @var SecurityContext 
     */
    protected $security;

    /**
     * The routing component
     * @var Router 
     */
    protected $router;

    /**
     * The session component
     * @var Session 
     */
    protected $session;

    /**
     * Basic constructor
     * 
     * @InjectParams({
     *      "security" = @Inject("security.token_storage"),
     *      "router" = @Inject("router"),
     *      "session" = @Inject("session"),
     * })
     * 
     * @param TokenStorageInterface $security The security component
     * @param RouterInterface $router The router component
     * @param SessionInterface $session The session component
     */
    public function __construct(TokenStorageInterface $security, RouterInterface $router, SessionInterface $session) {
        $this->security = $security;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * Determines if the user has a password that needs to be reset
     * 
     * If the user needs a password reset and the current route is not that of the password reset page,
     * the redirect response is set in the $event
     * @param GetResponseEvent $event The event to handle
     * @return null
     */
    public function isPasswordReset(GetResponseEvent $event) {
        if (stristr($event->getRequest()->get('_route'), '_assetic') !== false || stristr($event->getRequest()->get('_route'), self::CHANGE_PASSWORD_ROUTE) !== false || $event->getRequest()->get('_route') === null || stristr($event->getRequest()->get('_route'), '_wdt') !== false) {
            // don't do anything if it's not the master request or is requested by assetic or is the tool bar
            return;
        }
        
        if ($this->security->getToken() !== null) {
            
            if ($this->security->getToken()->getUser() instanceof User) {
                $user = $this->security->getToken()->getUser();

                //user is logged in
                if ($user->isPasswordReset()) {
                    //redirect to reset page unless already on reset page and allow css/js to go through                
                    if ($event->getRequest()->get('_route') != "_wdt") {
                        $this->session->set('locationReferUri', $event->getRequest()->getRequestUri());
                    }

                    $event->setResponse(new RedirectResponse($this->router->generate(self::CHANGE_PASSWORD_ROUTE)));
                }
            }
        }
    }

    /**
     * {@inheritDocs}
     */
    public static function getSubscribedEvents() {
        return array(
            'kernel.request' => array('isPasswordReset')
        );
    }

}
