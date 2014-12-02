<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use libphonenumber\PhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * Entity that handles Addresses
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @ORM\Table(name="kmj_toolkit_addresses")
 * @ORM\Entity()
 * @Assert\Callback(methods={"isZipcodeValid"})
 * @Assert\Callback(methods={"isStateValid"})
 */
class Address {

    /**
     * id for the address
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The first name for the address
     *
     * @ORM\Column(name="firstName", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Please enter a first name")
     * @var string
     */
    protected $firstName;

    /**
     * The last name for the address
     *
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Please enter a last name")
     * @var string
     */
    protected $lastName;

    /**
     * The street address
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Please enter a street address")
     * @Assert\Length(
     *          min="3", minMessage="Your address must have at least {{ limit }} characters",
     *          max="255", maxMessage="Your address cannont have at more than {{ limit }} characters"
     * )
     */
    protected $address;

    /**
     * The street address line 2
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    protected $address2;

    /**
     * The city
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Please enter a city")
     */
    protected $city;

    /**
     * The state
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", fetch="EAGER")
     * @ORM\JoinColumn(name="stateID", referencedColumnName="id", nullable=true)
     */
    protected $state;

    /**
     * The country
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country",  fetch="EAGER")
     * @ORM\JoinColumn(name="countryID", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank(message="Please select a country")
     */
    protected $country;

    /**
     * The zipcode
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=12, nullable=true)
     */
    protected $zipcode;

    /**
     * The short name of the address (for address books)
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * The phone number for the address
     *
     * @ORM\Column(name="phoneNumber", type="phone_number", nullable=true)
     * @var PhoneNumber
     * @AssertPhoneNumber(defaultRegion="GB")
     */
    protected $phoneNumber;

    /**
     * The company name for the address
     * @ORM\Column(name="companyName", type="string", length=255, nullable=true)
     * @var string
     */
    protected $companyName;

    /**
     * The longitude of the address
     *
     * @var float
     *
     * @ORM\Column(name="longitude", type="decimal", scale=7, precision=10, nullable=true)
     */
    protected $longitude;

    /**
     * The latitude of the address
     *
     * @var float
     *
     * @ORM\Column(name="latitude", type="decimal", scale=7, precision=10, nullable=true)
     */
    protected $latitude;

    /**
     * The latitude of the address
     * @var boolean
     * @ORM\Column(name="isResidential", type="boolean")
     */
    protected $isResidential;

    /**
     * Get the value of id for the address
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id for the address
     *
     * @param int $value id
     *
     * @return self
     */
    public function setId($value) {
        $this->id = $value;

        return $this;
    }

    /**
     * Get the value of The first name for the address
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set the value of The first name for the address
     *
     * @param string $value firstName
     *
     * @return self
     */
    public function setFirstName($value) {
        $this->firstName = $value;

        return $this;
    }

    /**
     * Get the value of The last name for the address
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set the value of The last name for the address
     *
     * @param string $value lastName
     *
     * @return self
     */
    public function setLastName($value) {
        $this->lastName = $value;

        return $this;
    }

    /**
     * Get the value of The street address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set the value of The street address
     *
     * @param string $value address
     *
     * @return self
     */
    public function setAddress($value) {
        if ($value != $this->address) {
            $this->resetGeoCoordinates();
        }

        $this->address = $value;

        return $this;
    }

    /**
     * Get the value of The street address line 2
     *
     * @return string
     */
    public function getAddress2() {
        return $this->address2;
    }

    /**
     * Set the value of The street address line 2
     *
     * @param string $value address2
     *
     * @return self
     */
    public function setAddress2($value) {
        if ($value != $this->address2) {
            $this->resetGeoCoordinates();
        }

        $this->address2 = $value;

        return $this;
    }

    /**
     * Get the value of The city
     *
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set the value of The city
     *
     * @param string $value city
     *
     * @return self
     */
    public function setCity($value) {
        if ($value != $this->city) {
            $this->resetGeoCoordinates();
        }

        $this->city = $value;

        return $this;
    }

    /**
     * Get the value of The state
     *
     * @return State
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set the value of The state
     *
     * @param State $value state
     *
     * @return self
     */
    public function setState(State $value = null) {
        if ($value != $this->state) {
            $this->resetGeoCoordinates();
        }

        $this->state = $value;

        return $this;
    }

    /**
     * Get the value of The country
     *
     * @return Country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set the value of The country
     *
     * @param Country $value country
     *
     * @return self
     */
    public function setCountry(Country $value = null) {
        if ($value != $this->country) {
            $this->resetGeoCoordinates();
        }

        $this->country = $value;

        return $this;
    }

    /**
     * Get the value of The zipcode
     *
     * @return string
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * Set the value of The zipcode
     *
     * @param string $value zipcode
     *
     * @return self
     */
    public function setZipcode($value) {
        $this->zipcode = $value;

        return $this;
    }

    /**
     * Get the value of The short name of the address (for address books)
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the value of The short name of the address (for address books)
     *
     * @param string $value name
     *
     * @return self
     */
    public function setName($value) {
        $this->name = $value;

        return $this;
    }

    /**
     * Get the value of The phone number for the address
     *
     * @return PhoneNumber
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * Set the value of The phone number for the address
     *
     * @param PhoneNumber $value phoneNumber
     *
     * @return self
     */
    public function setPhoneNumber(PhoneNumber $value = null) {
        $this->phoneNumber = $value;

        return $this;
    }

    /**
     * Get the value of The company name for the address
     *
     * @return string
     */
    public function getCompanyName() {
        return $this->companyName;
    }

    /**
     * Set the value of The company name for the address
     *
     * @param string $value companyName
     *
     * @return self
     */
    public function setCompanyName($value) {
        $this->companyName = $value;

        return $this;
    }

    /**
     * Get the value of The longitude of the address
     *
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Set the value of The longitude of the address
     *
     * @param float $value longitude
     *
     * @return self
     */
    public function setLongitude($value) {
        $this->longitude = $value;

        return $this;
    }

    /**
     * Get the value of The latitude of the address
     *
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set the value of The latitude of the address
     *
     * @param float $value latitude
     *
     * @return self
     */
    public function setLatitude($value) {
        $this->latitude = $value;

        return $this;
    }

    /**
     * Get the value of The latitude of the address
     *
     * @return boolean
     */
    public function getIsResidential() {
        return $this->isResidential;
    }

    /**
     * Set the value of The latitude of the address
     *
     * @param boolean $value isResidential
     *
     * @return self
     */
    public function setIsResidential($value) {
        $this->isResidential = $value;

        return $this;
    }

    /**
     * Basic constructor
     */
    public function __construct() {
        $this->name = "Default";
        $this->isResidential = true;
    }

    /**
     * Translates the address into a string (with html formating)
     *
     * @return string
     */
    public function __toString() {
        $string = null;

        if ($this->firstName !== null && $this->lastName !== null) {
            $string = $this->firstName . ' ' . $this->lastName . '<br />';
        }

        $string .= $this->address . '<br />';

        if ($this->address2 != "") {
            $string .= $this->address2 . '<br />';
        }

        if ($this->getState() instanceof State) {
            $string .= $this->city . ', ' . $this->getState()->getCode() . ' ' . $this->getCountry()->getCode() . ' ' . $this->zipcode;
        } else {
            $string .= $this->city . ' ' . ($this->getCountry() instanceof Country) ? $this->getCountry()->getCode() : "". ' ' . $this->zipcode;
        }

        return $string;
    }

    /**
     * Allows cloning of this class
     */
    public function __clone() {
        if ($this->id) {
            $this->id = null;
        }
    }

    /**
     * Sets the Geocoordinates for the address
     *
     * @param array $coordinates The geocoordintates
     * @return Address
     * @throws InvalidArgumentException
     */
    public function setGeoCoordinates(array $coordinates) {
        if (isset($coordinates['lat']) && isset($coordinates['lng'])) {
            $this->latitude = $coordinates['lat'];
            $this->longitude = $coordinates['lng'];
        } else {
            throw new InvalidArgumentException;
        }

        return $this;
    }

    /**
     * Clears the geo coordintates
     */
    private function resetGeoCoordinates() {
        $this->latitude = null;
        $this->longitude = null;
    }

    /**
     * Determines if the class is a valid address
     *
     * @return boolean
     */
    public function isValid() {
        if ($this->address == "" || $this->city == "" || $this->country == "") {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Duplicates the address without cloning
     *
     * @return Address
     */
    public function cloneAddress() {
        $newAddress = new Address();
        $newAddress->setCompanyName($this->getCompanyName());
        $newAddress->setFirstName($this->getFirstName());
        $newAddress->setLastName($this->getLastName());
        $newAddress->setCompanyName($this->getCompanyName());
        $newAddress->setLatitude($this->getLatitude());
        $newAddress->setLongitude($this->getLongitude());
        $newAddress->setAddress($this->getAddress());
        $newAddress->setAddress2($this->getAddress2());
        $newAddress->setCity($this->getCity());
        $newAddress->setState($this->getState());
        $newAddress->setCountry($this->getCountry());
        $newAddress->setZipcode($this->getZipcode());
        $newAddress->setIsResidential($this->getIsResidential());
        return $newAddress;
    }

    /**
     * Determines if a state is valid based on the current country
     *
     * @param ExecutionContext $context The form context
     * @return boolean
     */
    public function isStateValid(ExecutionContext $context) {
        $propertyPath = $context->getPropertyPath() . '.state';

        if ($this->getCountry() !== null) {
            if (($this->getCountry()->getCode() === "US" || $this->getCountry()->getCode() === "CA") && $this->getState() === null) {
                $context->setPropertyPath($propertyPath);
                $context->addViolation('Please select a state', array(), null);
                return FALSE;
            }
        }
    }

    /**
     * Determines if a zipcode is valid based on the current country
     *
     * @param ExecutionContext $context The form context
     * @return boolean
     */
    public function isZipcodeValid(ExecutionContext $context) {
        if ($this->getCountry() !== null) {
            if ($this->getCountry()->getZipCodeRequired() && $this->getZipcode() === null) {
                $propertyPath = $context->getPropertyPath() . '.zipcode';
                $context->setPropertyPath($propertyPath);
                $context->addViolation('Please enter a zipcode', array(), null);
                return FALSE;
            }
        }
    }

}
