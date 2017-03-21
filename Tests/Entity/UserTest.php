<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Tests\Entity;

use KMJ\ToolkitBundle\Entity\Role;
use KMJ\ToolkitBundle\Entity\User;
use PHPUnit_Framework_TestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Test class.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @coversDefaultClass \KMJ\ToolkitBundle\Entity\User
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    public function testFirstName()
    {
        $user = $this->getUser();
        $this->assertNull($user->getFirstName());
        $user->setFirstName('Tony');
        $this->assertEquals('Tony', $user->getFirstName());
    }

    public function testLastName()
    {
        $user = $this->getUser();
        $this->assertNull($user->getLastName());
        $user->setLastName('Soprano');
        $this->assertEquals('Soprano', $user->getLastName());
    }

    public function testPasswordReset()
    {
        $user = $this->getUser();
        $this->assertFalse($user->isPasswordReset());
        $user->setPasswordReset(true);
        $this->assertTrue($user->isPasswordReset());
    }

    public function testToString()
    {
        $user = $this->getUser();
        $this->assertTrue($user->__toString() === ' ');
        $user->setFirstName('Tony')
            ->setLastName('Soprano');

        $this->assertTrue($user->__toString() == 'Tony Soprano');
    }

    /**
     * @covers \KMJ\ToolkitBundle\Entity\User::buildUsername
     */
    public function testBuildUsername()
    {
        $user = $this->getUser();
        $user->setFirstName('Tony')
            ->setLastName('Soprano');

        $this->assertTrue($user->getUsername() === md5('Tony'.'Soprano'.time()));
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\Role
     */
    public function testUserRoles()
    {
        $user = $this->getUser();
        $this->assertTrue(sizeof($user->getRoles()) === 0);

        $role = new Role();
        $role->setDescription('Testing Role')
            ->setDisplayName('Testing Role')
            ->setName('ROLE_TESTING_ROLE');

        $user->addRole($role);
        $this->assertTrue(sizeof($user->getRoles()) === 1);

        $this->assertTrue($user->hasRole($role));

        $this->assertTrue($user->hasRoleByName('ROLE_TESTING_ROLE'));
        $this->assertFalse($user->hasRoleByName('ROLE_DOESNT_EXSIST'));

        $this->assertTrue(is_array($user->getRoles()));
        $this->assertTrue($user->getUserRoles() instanceof ArrayCollection);
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->getMockForAbstractClass('KMJ\ToolkitBundle\Entity\User');
    }
}
