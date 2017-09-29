<?php

namespace KMJ\ToolkitBundle\Tests\Listener;

use FOS\UserBundle\FOSUserEvents;
use KMJ\ToolkitBundle\Entity\User;
use KMJ\ToolkitBundle\Subscriber\PasswordResetSubscriber;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\Service\ToolkitServiceTest
 */
class PasswordResetSubscriberTest extends PHPUnit_Framework_TestCase
{
    protected $user;

    public function testGetSubscribedEvents()
    {
        $password = $this->getPasswordResetSubscriber();
        $events = $password->getSubscribedEvents();
        $this->assertTrue(sizeof($events) === 2);
        $this->assertTrue(key_exists('kernel.request', $events));
        $this->assertTrue(key_exists(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $events));
    }

    /**
     * @return PasswordResetSubscriber
     */
    protected function getPasswordResetSubscriber()
    {
        $security = $this->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();

        $router->method('generate')
            ->will($this->returnValue('http://example.com/login'));

        $session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();

        $token = $this->getMockBuilder(AbstractToken::class)
            ->getMock();

        $this->user = $this->getMockForAbstractClass(User::class);

        $security->setToken($token);

        $token->method('getUser')
            ->will($this->returnValue($this->user));

        $security->method('getToken')
            ->will($this->returnValue($token));

        return new PasswordResetSubscriber(
            $security, $router, $session, [
                'password_reset_route' => 'fos_user_change_password',
            ]
        );
    }

    public function testIsPasswordReset()
    {
        $password = $this->getPasswordResetSubscriber();

        $testEvent = $this->getEvent();

        $testRequest = new Request([], [], ['_route' => 'test']);

        $testEvent->method('getRequest')
            ->will($this->returnValue($testRequest));

        $password->isPasswordReset($testEvent);
        $this->assertNull($testEvent->getResponse());

        $this->user->setPasswordReset(true);

        $password->isPasswordReset($testEvent);
        $this->assertTrue($testEvent->getResponse() instanceof RedirectResponse);

        $asseticEvent = $this->getEvent();

        $asseticRequest = new Request([], [], ['_route' => '_assetic']);

        $asseticEvent->method('getRequest')
            ->will($this->returnValue($asseticRequest));

        $password->isPasswordReset($asseticEvent);
        $this->assertNull($asseticEvent->getResponse());

        $toolbarEvent = $this->getEvent();
        $toolbarRequest = new Request([], [], ['_route' => '_wdt']);
        $toolbarEvent->method('getRequest')
            ->will($this->returnValue($toolbarRequest));

        $password->isPasswordReset($toolbarEvent);
        $this->assertNull($toolbarEvent->getResponse());

        $changePassEvent = $this->getEvent();
        $changePassRequest = new Request([], [], ['_route' => 'fos_user_change_password']);
        $changePassEvent->method('getRequest')
            ->will($this->returnValue($changePassRequest));

        $password->isPasswordReset($changePassEvent);
        $this->assertNull($changePassEvent->getResponse());
    }

    /**
     * @return GetResponseEvent
     */
    protected function getEvent()
    {
        return $this->getMockBuilder(GetResponseEvent::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRequest'])
            ->getMock();
    }
}
