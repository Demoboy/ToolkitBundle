<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Tests\Entity;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\Entity\Address
 */
class AddressTest extends PHPUnit_Framework_TestCase
{
    public function testStreet()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getStreet());
        $address->setStreet('123 Elm St SE');
        $this->assertTrue($address->getStreet() === '123 Elm St SE');
    }

    public function testUnit()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getUnit());
        $address->setUnit('Unit 3B');
        $this->assertTrue($address->getUnit() === 'Unit 3B');
    }

    public function testCity()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getCity());
        $address->setCity('Seattle');
        $this->assertTrue($address->getCity() === 'Seattle');
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\State
     */
    public function testState()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getState());
        $address->setState(new \KMJ\ToolkitBundle\Entity\State());
        $this->assertTrue($address->getState() instanceof \KMJ\ToolkitBundle\Entity\State);
        $address->setState(null);
        $this->assertNull($address->getState());
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\Country
     */
    public function testCountry()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getCountry());
        $address->setCountry(new \KMJ\ToolkitBundle\Entity\Country());
        $this->assertTrue($address->getCountry() instanceof \KMJ\ToolkitBundle\Entity\Country);
        $address->setCountry(null);
        $this->assertNull($address->getCountry());
    }

    public function testZipcode()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getZipcode());
        $address->setZipcode('98012');
        $this->assertTrue($address->getZipcode() === '98012');
    }

    public function testName()
    {
        $address = $this->getAddress();
        $this->assertTrue($address->getName() === 'Default');
        $address->setName('Seattle Address');
        $this->assertTrue($address->getName() === 'Seattle Address');
    }

    public function testLongitude()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getLongitude());
        $address->setLongitude(38.3452);
        $this->assertTrue($address->getLongitude() === 38.3452);
    }

    public function testLatitude()
    {
        $address = $this->getAddress();
        $this->assertNull($address->getLatitude());
        $address->setLatitude(-38.3452);
        $this->assertTrue($address->getLatitude() === -38.3452);
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\State
     * @uses \KMJ\ToolkitBundle\Entity\Country
     */
    public function testToString()
    {
        $address = $this->getAddress();

        $address->setStreet('123 Elm Street SE')
            ->setCity('Seattle')
            ->setZipcode('98002');

        $this->assertTrue($address->__toString() === '123 Elm Street SE Seattle 98002', 'no country and state');

        $state = new \KMJ\ToolkitBundle\Entity\State();
        $state->setName('Washington')
            ->setCode('WA');

        $country = new \KMJ\ToolkitBundle\Entity\Country();
        $country->setName('United States')
            ->setCode('US');

        $address->setState($state)
            ->setCountry($country);

        $this->assertTrue($address->__toString() === '123 Elm Street SE Seattle, WA US 98002');

        $address->setUnit('Unit 3B');

        $this->assertTrue($address->__toString() === '123 Elm Street SE Unit 3B Seattle, WA US 98002');
    }

    public function testResidential()
    {
        $address = $this->getAddress();
        $this->assertNull($address->isResidential());

        $address->setResidential(true);

        $this->assertTrue($address->isResidential());
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\Country
     */
    public function testIsZipcodeValid()
    {
        $address = $this->getAddress();
        $country = new \KMJ\ToolkitBundle\Entity\Country();

        $mock = $this->getMockBuilder("Symfony\Component\Validator\Context\ExecutionContextInterface")
            ->disableOriginalConstructor()
            ->setMethods(['addViolationAt'])
            ->getMock();

        $this->assertNull($address->isZipcodeValid($mock));
        $address->setCountry($country);

        $this->assertTrue($address->isZipcodeValid($mock));
        $country->setZipCodeRequired(true);

        $this->assertFalse($address->isZipcodeValid($mock));
        $address->setZipcode('98012');

        $this->assertTrue($address->isZipcodeValid($mock));
    }

    /**
     * @uses \KMJ\ToolkitBundle\Entity\Country
     */
    public function testIsStateValid()
    {
        $address = $this->getAddress();
        $country = new \KMJ\ToolkitBundle\Entity\Country();
        $state = new \KMJ\ToolkitBundle\Entity\State();

        $mock = $this->getMockBuilder("Symfony\Component\Validator\Context\ExecutionContextInterface")
            ->disableOriginalConstructor()
            ->setMethods(['addViolationAt'])
            ->getMock();

        $this->assertNull($address->isStateValid($mock));
        $address->setCountry($country);
        $this->assertTrue($address->isStateValid($mock));
        $country->setCode('US');
        $this->assertFalse($address->isStateValid($mock));
        $address->setState($state);
        $this->assertTrue($address->isStateValid($mock));
    }

    public function testIsValid()
    {
        $address = $this->getAddress();
        $country = new \KMJ\ToolkitBundle\Entity\Country();

        $address->setCountry($country);
        $this->assertFalse($address->isValid());
        $address->setCity('Seattle');
        $this->assertFalse($address->isValid());
        $address->setStreet('123 Elm st');
        $this->assertTrue($address->isValid());
    }

    protected function getAddress()
    {
        return new \KMJ\ToolkitBundle\Entity\Address();
    }
}
