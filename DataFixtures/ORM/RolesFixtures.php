<?php

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use KMJ\ToolkitBundle\Entity\Role;

class RolesFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 90;
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        $role1 = new Role();
        $role1->setName('user')
                ->setDisplayName('Standard User')
                ->setDescription('Role for standard user level access.');

        $role2 = new Role();
        $role2->setName('admin')
                ->setDisplayName('Administrator')
                ->setDescription('Role for site administrator. Allows the user that has the role to change site settings');
        
        $this->setReference('role_user', $role1);
        $this->setReference('role_admin', $role2);
        $manager->persist($role1);
        $manager->persist($role2);
        $manager->flush();
    }

}

?>