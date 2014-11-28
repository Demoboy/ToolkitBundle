<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Loads a super user into the system to allow logins. It pulls the information from the serivce configuration.
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class UserFixtures extends AbstractFixture implements OrderedFixtureInterface, \Symfony\Component\DependencyInjection\ContainerAwareInterface {

    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     * @return int The order to execute the fixture
     */
    public function getOrder() {
        return 100;
    }

    /**
     * {@inheritDoc}
     * 
     * @param \Doctrine\Common\Persistence\ObjectManager $manager entity manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        $userManager = $this->container->get('fos_user.user_manager');

        $tk = $this->container->get("toolkit");

        if ($tk->overrideFixture() == true) {
            return;
        }

        $adminUser = $tk->createAdminUser()
                ->addRole($this->getReference('role_super_admin'));

        $this->setReference("superuser", $adminUser);

        $userManager->updateUser($adminUser);

        $manager->flush();
    }

}
