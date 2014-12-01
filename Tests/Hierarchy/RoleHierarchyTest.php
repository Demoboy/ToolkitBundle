<?php

namespace KMJ\ToolkitBundle\Tests\Hierarchy;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-28 at 17:21:36.
 */
class RoleHierarchyTest extends \PHPUnit_Framework_TestCase {

    public function testBuildRolesTree() {
        $hierarchy = $this->getHierarchy();
        $roleHierarchy = $hierarchy->buildRolesTree();

        $expectedHierarchy = array(
            "ROLE_SUPER_ADMIN" => array(
                "ROLE_ADMIN",
            ),
            "ROLE_ADMIN" => array(
                "ROLE_USER",
            ),
        );

        $this->assertTrue($roleHierarchy === $expectedHierarchy);
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\Role
     */
    protected function getHierarchy() {
        $em = $this->getMockBuilder("Doctrine\ORM\EntityManager")
                ->disableOriginalConstructor()
                ->getMock();

        $repo = $this->getMockBuilder("Doctrine\ORM\EntityRepository")
                ->disableOriginalConstructor()
                ->getMock();

        $superAdmin = new \KMJ\ToolkitBundle\Entity\Role();
        $superAdmin->setName("SUPER_ADMIN");

        $admin = new \KMJ\ToolkitBundle\Entity\Role();
        $admin->setName("ADMIN")
                ->setParent($superAdmin);

        $user = new \KMJ\ToolkitBundle\Entity\Role();
        $user->setName("USER")
                ->setParent($admin);

        $roles = [$superAdmin, $admin, $user];

        $repo->method("findAll")
                ->will($this->returnValue($roles));

        $em->method("getRepository")
                ->will($this->returnValue($repo));

        $test = $em->getRepository("sdfsd");

        return new \KMJ\ToolkitBundle\Hierarchy\RoleHierarchy(array(), $em);
    }

}
