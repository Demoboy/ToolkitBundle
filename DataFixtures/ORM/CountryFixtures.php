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
use KMJ\ToolkitBundle\Entity\Country;

/**
 * Loads \KMJ\ToolkitBundle\Entity\Country from csv file.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class CountryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @return int The order to execute the fixture
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * {@inheritdoc}
     *
     * @param ObjectManager $manager entity manager
     */
    public function load(ObjectManager $manager)
    {
        $fileHandle = fopen(__DIR__.'/Fixtures/countries.csv', 'r');

        $country = [];
        while (($data = fgetcsv($fileHandle)) !== false) {
            $country = new Country();
            $country->setName($data[1]);
            $country->setCode($data[2]);
            $country->setZipCodeRequired($data[3]);

            $manager->persist($country);
        }

        $manager->flush();
    }
}
