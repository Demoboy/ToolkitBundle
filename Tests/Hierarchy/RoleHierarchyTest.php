<?php

namespace KMJ\ToolkitBundle\Tests\Hierarchy;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use KMJ\ToolkitBundle\Entity\Role;
use KMJ\ToolkitBundle\Hierarchy\RoleHierarchy;
use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-28 at 17:21:36.
 */
class RoleHierarchyTest extends PHPUnit_Framework_TestCase
{

    public function testReachableRoles()
    {
        $hierarchy = $this->getHierarchy();

        $superAdminRole = new Role();
        $superAdminRole->setName('ROLE_SUPER_ADMIN');

        $reachableSuperAdminRoles = $hierarchy->getReachableRoles([$superAdminRole]);

        $roleInArray = function ($roleName, $roles) {
            foreach ($roles as $r) {
                if ($r->getRole() === $roleName) {
                    return true;
                }
            }
            return false;
        };

        $this->assertTrue($roleInArray("ROLE_SUPER_ADMIN",
                $reachableSuperAdminRoles),
            "Super Admin does not have super admin rights");
        $this->assertTrue($roleInArray("ROLE_ADMIN", $reachableSuperAdminRoles),
            "Super Admin does not have admin rights");
        $this->assertTrue($roleInArray("ROLE_USER", $reachableSuperAdminRoles),
            "Super Admin does not have user rights");

        $adminRole = new Role();
        $adminRole->setName("ROLE_ADMIN");

        $reachableAdminRoles = $hierarchy->getReachableRoles([$adminRole]);

        $this->assertFalse($roleInArray("ROLE_SUPER_ADMIN", $reachableAdminRoles),
            "Admin has super admin rights");
        $this->assertTrue($roleInArray("ROLE_ADMIN", $reachableAdminRoles),
            "Admin does not have admin rights");
        $this->assertTrue($roleInArray("ROLE_USER", $reachableAdminRoles),
            "Admin does not have user rights");

        $multiRoles = $hierarchy->getReachableRoles([$superAdminRole, $adminRole]);

        $this->assertTrue($roleInArray("ROLE_SUPER_ADMIN", $multiRoles),
            "Super Admin does not have super admin rights");
        $this->assertTrue($roleInArray("ROLE_ADMIN", $multiRoles),
            "Super Admin does not have admin rights");
        $this->assertTrue($roleInArray("ROLE_USER", $multiRoles),
            "Super Admin does not have user rights");
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\Role
     */
    protected function getHierarchy()
    {
        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRepository'])
            ->getMock();

        $repo = $this->getMockBuilder(EntityRepository::class)
            ->setMethods(["findAll"])
            ->disableOriginalConstructor()
            ->getMock();

        $superAdmin = new Role();
        $superAdmin->setName("SUPER_ADMIN");

        $admin = new Role();
        $admin->setName("ADMIN")
            ->setParent($superAdmin);

        $user = new Role();
        $user->setName("USER")
            ->setParent($admin);

        $roles = [$superAdmin, $admin, $user];

        $repo->method("findAll")
            ->will($this->returnValue($roles));

        $em->method("getRepository")
            ->will($this->returnValue($repo));

        $test = $em->getRepository("sdfsd");

        return new RoleHierarchy(array(), $em);
    }
}
