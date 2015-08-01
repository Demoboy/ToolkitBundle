<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Tests\Entity;

use PHPUnit_Framework_TestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\Entity\Country
 */
class CountryTest extends PHPUnit_Framework_TestCase
{

    public function testToString()
    {
        $country = $this->getCountry();
        $country->setName("Country");
        $this->assertTrue($country->__toString() === "Country");
    }

    public function testName()
    {
        $country = $this->getCountry();
        $this->assertNull($country->getName());
        $country->setName("Country");
        $this->assertTrue($country->getName() === "Country");
    }

    public function testCode()
    {
        $country = $this->getCountry();
        $this->assertNull($country->getCode());
        $country->setCode("US");
        $this->assertTrue($country->getCode() === "US");
    }

    public function testZipcodeRequired()
    {
        $country = $this->getCountry();
        $this->assertFalse($country->isZipCodeRequired());
        $country->setZipCodeRequired(true);
        $this->assertTrue($country->isZipCodeRequired());
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\State 
     */
    public function testStates()
    {
        $country = $this->getCountry();
        $this->assertTrue($country->getStates() instanceof ArrayCollection);
        $this->assertTrue(sizeof($country->getStates()) === 0);

        $state = new \KMJ\ToolkitBundle\Entity\State();
        $state->setName("state");

        $country->getStates()->add($state);
        $this->assertTrue(sizeof($country->getStates()) === 1);
    }

    /**
     * @return \KMJ\ToolkitBundle\Entity\Country
     */
    protected function getCountry()
    {
        return new \KMJ\ToolkitBundle\Entity\Country();
    }
}
