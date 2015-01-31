<?php

namespace KMJ\ToolkitBundle\Tests\Service;

use KMJ\ToolkitBundle\Service\ToolkitService;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\Service\ToolkitServiceTest
 */
class ToolkitServiceTest extends ToolkitTest {

    public function testCreateUser() {
        $toolkit = $this->getToolkit();
        $user = $toolkit->createAdminUser();

        $this->assertTrue($user instanceof \KMJ\ToolkitBundle\Entity\User);
        $this->assertTrue($user->getFirstName() === "Tony");
        $this->assertTrue($user->getLastName() === "Soprano");
        $this->assertTrue($user->getUsername() === "mobster1");
        $this->assertTrue($user->getEmail() === "tonysoprano@gmail.com");
        $this->assertTrue($user->getPlainPassword() === "password");
    }

    public function testCreateAdminUserArray() {
        $toolkit = $this->getToolkit();
        $user = $toolkit->createAdminUserArray();

        $this->assertTrue(is_array($user));
        $this->assertEquals($user['firstName'], "Tony");
        $this->assertEquals($user['lastName'], "Soprano");
        $this->assertEquals($user['email'], "tonysoprano@gmail.com");
        $this->assertEquals($user['username'], "mobster1");
        $this->assertTrue($user['enabled']);
        $this->assertEquals($user['plainPassword'], "password");
    }

    public function testOverrideFixture() {
        $toolkit = $this->getToolkit();

        $this->assertFalse($toolkit->overrideFixture());
        $toolkit->overrideFixture(true);
        $this->assertTrue($toolkit->overrideFixture());
    }

}
