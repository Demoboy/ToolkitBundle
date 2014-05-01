<?php

namespace KMJ\ToolkitBundle\Listener;

use KMJ\ToolkitBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * @Service()
 * @Tag("kernel.event_subscriber", attributes={"event" = "kernel.request", "method" = "isPasswordReset"})
 */
class PasswordResetListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface {

    protected $security;
    protected $router;
    protected $session;
    
    const CHANGE_PASSWORD_ROUTE = "s";

    /**
     * @InjectParams({
     *      "security" = @Inject("security.context"),
     *      "router" = @Inject("router"),
     *      "session" = @Inject("session"),
     * })
     */
    public function __construct($security, $router, $session) {
        $this->security = $security;
        $this->router = $router;
        $this->session = $session;
    }

    public function isPasswordReset($event) {
        if (stristr($event->getRequest()->get('_route'), '_assetic') !== false || stristr($event->getRequest()->get('_route'), self::CHANGE_PASSWORD_ROUTE) !== false || $event->getRequest()->get('_route') == null || stristr($event->getRequest()->get('_route'), '_wdt') !== false) {
            // don't do anything if it's not the master request or is requested by assetic or is the tool bar
            return;
        }
        
        if ($this->security->getToken() != null) {
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

    public static function getSubscribedEvents() {
        return array(
            'kernel.request' => array('isPasswordReset')
        );
    }

}
