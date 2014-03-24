<?php

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use KMJ\ToolkitBundle\Entity\Country;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CountryFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 10;
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager) {
        $fh = fopen('app/csvDumps/countries.csv', 'r');

        $country = array();
        while (($data = fgetcsv($fh)) !== false) {
            $country = new Country();
            $country->setName($data[1]);
            $country->setCode($data[2]);
            $country->setZipCodeRequired($data[3]);

            $manager->persist($country);
        }

        $manager->flush();
    }

}

?>