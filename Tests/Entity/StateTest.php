<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Tests\Entity;

use KMJ\ToolkitBundle\Entity\State;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\Entity\State
 */
class StateTest extends PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $state = $this->getState();
        $this->assertNull($state->getName());
        $state->setName('State');
        $this->assertTrue($state->getName() == 'State');
    }

    public function testCode()
    {
        $state = $this->getState();
        $this->assertNull($state->getCode());
        $state->setCode('ST');
        $this->assertTrue($state->getCode() == 'ST');
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\Country
     */
    public function testCountry()
    {
        $country = new \KMJ\ToolkitBundle\Entity\Country();
        $country->setCode('US')
            ->setName('United States')
            ->setZipCodeRequired(true);

        $state = $this->getState();
        $this->assertNull($state->getCountry());
        $state->setCountry($country);
        $this->assertTrue($state->getCountry() instanceof \KMJ\ToolkitBundle\Entity\Country);
    }

    public function testToString()
    {
        $state = $this->getState();
        $this->assertNull($state->__toString());
        $state->setName('State');
        $this->assertTrue($state->__toString() === 'State');
    }

    protected function getState()
    {
        return new State();
    }
}
