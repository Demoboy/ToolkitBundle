<?php

namespace KMJ\ToolkitBundle\Tests\Service;

use KMJ\ToolkitBundle\Service\ToolkitService;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\ToolkitTest
 */
abstract class ToolkitTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers KMJ\ToolkitBundle\Service\ToolkitService::__construct
     */
    protected function getToolkit() {
        $config = array(
            "administrator" => array(
                "firstname" => "Tony",
                "lastname" => "Soprano",
                "username" => "mobster1",
                "email" => "tonysoprano@gmail.com",
                "password" => "password",
            ),
            "rootdir" => __DIR__ . "/../",
            "enckey" => "enckey",
        );

        $user = $this->getMockForAbstractClass("KMJ\ToolkitBundle\Entity\User");

        $fosUser = $this->getMockBuilder("FOS\UserBundle\Model\UserManager")
                ->disableOriginalConstructor()
                ->getMock();

        $fosUser->method("createUser")
                ->will($this->returnValue($user));

        return new ToolkitService($config, $fosUser);
    }

}
