<?php

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use KMJ\ToolkitBundle\Entity\State;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StateFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    private $_container;

    public function getOrder() {
        return 11;
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        $fh = fopen('app/csvDumps/states.csv', 'r');

        $repo = $this->_container->get('doctrine')->getManager()->getRepository('KMJToolkitBundle:Country');
        
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

    public function setContainer(ContainerInterface $container = null) {
        $this->_container = $container;
    }

}

?>