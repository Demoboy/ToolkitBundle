<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DataFixtures\ORM;

use KMJ\ToolkitBundle\Entity\Country;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Loads \KMJ\ToolkitBundle\Entity\Country from csv file
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class CountryFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     * @return int The order to execute the fixture
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * {@inheritDoc}
     * 
     * @param \Doctrine\Common\Persistence\ObjectManager $manager entity manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $fh = fopen(__DIR__.'/Fixtures/countries.csv', 'r');

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