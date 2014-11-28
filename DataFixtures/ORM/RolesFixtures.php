<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use KMJ\ToolkitBundle\Entity\Role;

/**
 * Loads basic roles into the database and sets references to them
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class RolesFixtures extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     * 
     * @return int The order to execute the fixture
     */
    public function getOrder() {
        return 90;
    }

    /**
     * {@inheritDoc}
     * 
     * @param \Doctrine\Common\Persistence\ObjectManager $manager entity manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        $role3 = new Role();
        $role3->setName("super_admin")
                ->setDisplayName("Super Admin")
                ->setDescription("Role for developer. Use with caution");

        $role2 = new Role();
        $role2->setName('admin')
                ->setDisplayName('Administrator')
                ->setParent($role3)
                ->setDescription('Role for site administrator. Allows the user that has the role to change site settings');

        $role1 = new Role();
        $role1->setName('user')
                ->setDisplayName('Standard User')
                ->setParent($role2)
                ->setDescription('Role for standard user level access.');

        $this->setReference('role_user', $role1);
        $this->setReference('role_admin', $role2);
        $this->setReference('role_super_admin', $role3);

        $manager->persist($role3);
        $manager->persist($role1);
        $manager->persist($role2);
        $manager->flush();
    }

}
