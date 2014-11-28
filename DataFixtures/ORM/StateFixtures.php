<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use KMJ\ToolkitBundle\Entity\State;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Loads \KMJ\ToolkitBundle\Entity\State from csv file and creates relationship to a country
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class StateFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;

    /**
     * {@inheritDoc}
     * 
     * @return int The order to execute the fixture
     */
    public function getOrder() {
        return 11;
    }

    /**
     * {@inheritDoc}
     * 
     * @param \Doctrine\Common\Persistence\ObjectManager $manager entity manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        $fh = fopen('app/csvDumps/states.csv', 'r');

        $repo = $this->container->get('doctrine')->getManager()->getRepository('KMJToolkitBundle:Country');

        $state = array();
        while (($data = fgetcsv($fh)) !== false) {
            $state = new State();
            $state->setName($data[1]);
            $state->setCode($data[2]);
            $state->setCountry($repo->findOneByCode($data[3]));
            $state->setTaxRate($data[4]);
            $manager->persist($state);
        }

        $manager->flush();
    }

}
