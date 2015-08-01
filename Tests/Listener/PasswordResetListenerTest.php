<?php

namespace KMJ\ToolkitBundle\Tests\Listener;

use KMJ\ToolkitBundle\Listener\PasswordResetListener;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\Service\ToolkitServiceTest
 */
class PasswordResetListenerTest extends PHPUnit_Framework_TestCase
{

    protected $user;

    public function testGetSubscribedEvents()
    {
        $password = $this->getPasswordResetListener();
        $events = $password->getSubscribedEvents();
        $this->assertTrue(sizeof($events) === 1);
    }

    public function testIsPasswordReset()
    {
        $password = $this->getPasswordResetListener();

        $testEvent = $this->getEvent();

        $testRequest = new Request(array(), array(), array("_route" => "test"));

        $testEvent->method('getRequest')
            ->will($this->returnValue($testRequest));

        $password->isPasswordReset($testEvent);
        $this->assertNull($testEvent->getResponse());

        $this->user->setPasswordReset(true);

        $password->isPasswordReset($testEvent);
        $this->assertTrue($testEvent->getResponse() instanceof RedirectResponse);

        $asseticEvent = $this->getEvent();

        $asseticRequest = new Request(array(), array(), array("_route" => "_assetic"));

        $asseticEvent->method('getRequest')
            ->will($this->returnValue($asseticRequest));

        $password->isPasswordReset($asseticEvent);
        $this->assertNull($asseticEvent->getResponse());

        $toolbarEvent = $this->getEvent();
        $toolbarRequest = new Request(array(), array(), array("_route" => "_wdt"));
        $toolbarEvent->method('getRequest')
            ->will($this->returnValue($toolbarRequest));

        $password->isPasswordReset($toolbarEvent);
        $this->assertNull($toolbarEvent->getResponse());

        $changePassEvent = $this->getEvent();
        $changePassRequest = new Request(array(), array(), array("_route" => "change_password"));
        $changePassEvent->method('getRequest')
            ->will($this->returnValue($changePassRequest));

        $password->isPasswordReset($changePassEvent);
        $this->assertNull($changePassEvent->getResponse());
    }

    /**
     * 
     * @return PasswordResetListener
     */
    protected function getPasswordResetListener()
    {
        $security = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage')
            ->disableOriginalConstructor()
            ->getMock();

        $router = $this->getMockBuilder('Symfony\Component\Routing\Router')
            ->disableOriginalConstructor()
            ->getMock();

        $router->method("generate")
            ->will($this->returnValue("http://example.com/login"));

        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()
            ->getMock();

        $token = $this->getMockBuilder("Symfony\Component\Security\Core\Authentication\Token\AbstractToken")
            ->getMock();

        $this->user = $this->getMockForAbstractClass("KMJ\ToolkitBundle\Entity\User");

        $security->setToken($token);

        $token->method('getUser')
            ->will($this->returnValue($this->user));

        $security->method("getToken")
            ->will($this->returnValue($token));

        return new PasswordResetListener($security, $router, $session);
    }

    /**
     * @return GetResponseEvent
     */
    protected function getEvent()
    {
        return $this->getMockBuilder("Symfony\Component\HttpKernel\Event\GetResponseEvent")
                ->disableOriginalConstructor()
                ->setMethods(array("getRequest"))
                ->getMock();
    }
}
