<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Loads a super user into the system to allow logins. It pulls the information from the serivce configuration.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class UserFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @return int The order to execute the fixture
     */
    public function getOrder()
    {
        return 100;
    }

    /**
     * {@inheritdoc}
     *
     * @param ObjectManager $manager entity manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $toolkit = $this->container->get('toolkit');

        if ($toolkit->overrideFixture() == true) {
            return;
        }

        $adminUser = $toolkit->createAdminUser()
            ->addRole($this->getReference('role_super_admin'));

        $this->setReference('superuser', $adminUser);

        $userManager->updateUser($adminUser);

        $manager->flush();
    }
}
