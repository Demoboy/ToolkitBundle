<?php

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface, \Symfony\Component\DependencyInjection\ContainerAwareInterface {

    protected $_container;

    public function getOrder() {
        return 100;
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        $userManager = $this->_container->get('fos_user.user_manager');

        $tk = $this->_container->get("toolkit");
        
        if ($tk->overrideFixture() == true) {
            return;
        }

        $adminUser = $tk->createAdminUser()
                ->addRole($this->getReference('role_super_admin'));
        
        $this->setReference("superuser", $adminUser);

        $userManager->updateUser($adminUser);

        $manager->flush();
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        $this->_container = $container;
    }

}