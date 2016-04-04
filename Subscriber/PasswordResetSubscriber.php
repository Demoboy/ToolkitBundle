<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Subscriber;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use KMJ\ToolkitBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Listens for a page load and checks the database to determine if a user needs to have
 * thier password reset
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class PasswordResetSubscriber implements EventSubscriberInterface
{
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
     * The route to redirect to
     * @var string
     */
    private $route;

    /**
     * Basic constructor
     * 
     * @param TokenStorageInterface $security The security component
     * @param RouterInterface $router The router component
     * @param SessionInterface $session The session component
     */
    public function __construct(TokenStorageInterface $security,
                                RouterInterface $router,
                                SessionInterface $session, $config)
    {
        $this->security = $security;
        $this->router = $router;
        $this->session = $session;
        $this->route = $config['password_reset_route'];
    }

    /**
     * Determines if the user has a password that needs to be reset
     * 
     * If the user needs a password reset and the current route is not that of the password reset page,
     * the redirect response is set in the $event
     * @param GetResponseEvent $event The event to handle
     * @return null
     */
    public function isPasswordReset(GetResponseEvent $event)
    {
        if (stristr($event->getRequest()->get('_route'), '_assetic') !== false || stristr($event->getRequest()->get('_route'),
                $this->route) !== false || $event->getRequest()->get('_route') === null
            || stristr($event->getRequest()->get('_route'), '_wdt') !== false) {
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
                        $this->session->set('locationReferUri',
                            $event->getRequest()->getRequestUri());
                    }

                    $event->setResponse(new RedirectResponse($this->router->generate($this->route)));
                }
            }
        }
    }

    /**
     * Handles the user entity after the password has been successfully reset 
     * and redirects the user to previous page they were visiting
     * @param FormEvent $event
     */
    public function passwordChanged(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        $user->setPasswordReset(false);

        $response = new RedirectResponse($this->session->get("locationReferUri"));

        $event->setResponse($response);
        $this->session->remove("locationReferUri");
    }

    /**
     * {@inheritDocs}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'kernel.request' => ['isPasswordReset'],
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => ["passwordChanged"],
        );
    }
}
