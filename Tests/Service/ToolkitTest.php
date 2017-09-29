<?php

namespace KMJ\ToolkitBundle\Tests\Service;

use FOS\UserBundle\Model\UserManager;
use KMJ\ToolkitBundle\Entity\User;
use KMJ\ToolkitBundle\Service\ToolkitService;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\ToolkitTest
 */
abstract class ToolkitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KMJ\ToolkitBundle\Service\ToolkitService::__construct
     */
    protected function getToolkit()
    {
        $config = [
            'administrator' => [
                'firstname' => 'Tony',
                'lastname' => 'Soprano',
                'username' => 'mobster1',
                'email' => 'tonysoprano@gmail.com',
                'password' => 'password',
            ],
            'load_user_fixtures' => true,
            'rootdir' => __DIR__.'/..',
            'enckey' => 'enckey',
        ];

        $user = $this->getMockForAbstractClass(User::class);

        $fosUser = $this->getMockBuilder(UserManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fosUser->method('createUser')
            ->will($this->returnValue($user));

        return new ToolkitService($config, $fosUser);
    }
}
