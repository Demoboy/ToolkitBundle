<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Tests\Entity;

use DateTime;
use KMJ\ToolkitBundle\Entity\Role;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\Entity\Role
 */
class RoleTest extends PHPUnit_Framework_TestCase {
    
    public function testParent() {
        $role = $this->getRole();
        $parentRole = new Role();
        $parentRole->setName("Parent");
        $role->setParent($parentRole);
        $this->assertTrue($role->getParent()->getName() === "ROLE_PARENT");
    }
    
    public function testToString() {
        $role = $this->getRole();
        $role->setDisplayName("Testing Role");
        $this->assertTrue($role->__toString() === "Testing Role");
    }
    
    public function testDescription() {
        $role = $this->getRole();
        $role->setDescription("Testing Description");
        $this->assertTrue($role->getDescription() === "Testing Description");
    }

    public function testCreatedOn() {
        $role = $this->getRole();
        $this->assertTrue($role->getCreatedOn() instanceof DateTime);
    }
    
    public function testName() {
        $role1 = $this->getRole();
        
        $role1->setName("ROLE_TESTING");
        $this->assertTrue($role1->getName() === "ROLE_TESTING");
        
        $role2 = $this->getRole();
        $role2->setName("Testing");
        $this->assertTrue($role2->getName() === "ROLE_TESTING");
    }

    public function testRoleInterface() {
        $role = $this->getRole();
        $role->setName("ROLE_TESTING_ROLE")
                ->setDisplayName("Testing Role")
                ->setDescription("Testing Role Description");
        
        $this->assertTrue($role->getRole() === "ROLE_TESTING_ROLE");
    }
    
    
    /**
     * @return Role
     */
    protected function getRole() {
        return new Role();
    }

}
