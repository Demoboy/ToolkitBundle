<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Please enter a street address")
     * @Assert\Length(
     *          min="3", minMessage="Your address must have at least {{ limit }} characters",
     *          max="255", maxMessage="Your address cannont have at more than {{ limit }} characters"
     * )
     */
    protected $street;

    /**
     * The street address (line 2)
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $unit;

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
     * @var libphonenumber\PhoneNumber
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
     * Boolean to determine if the address is a residental address
     * @var boolean
     * @ORM\Column(name="isResidential", type="boolean", nullable=true)
     */
    protected $residential;

    /**
     * Get the value of id for the address
     * @codeCoverageIgnore
     * @return int
     */
    public function getId() {
        return $this->id;
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
    public function getStreet() {
        return $this->street;
    }

    /**
     * Set the value of The street address
     *
     * @param string $value street address
     *
     * @return self
     */
    public function setStreet($value) {
        $this->street = $value;

        return $this;
    }

    /**
     * Get the value of The street address line 2
     *
     * @return string
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * Set the value of The street address (line 2)
     *
     * @param string $value unit
     *
     * @return self
     */
    public function setUnit($value) {
        $this->unit = $value;

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
    public function setState(State $value) {
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
    public function setCountry(Country $value) {
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
     * @return libphonenumber\PhoneNumber
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * Set the value of The phone number for the address
     *
     * @param libphonenumber\PhoneNumber $value phoneNumber
     *
     * @return self
     */
    public function setPhoneNumber(PhoneNumber $value) {
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
     * Basic constructor
     */
    public function __construct() {
        $this->name = "Default";
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

        $string .= $this->street . '<br />';

        if ($this->unit != "") {
            $string .= $this->unit . '<br />';
        }

        if ($this->getState() instanceof State) {
            $string .= $this->city . ', ' . $this->getState()->getCode() . ' ' . $this->getCountry()->getCode() . ' ' . $this->zipcode;
        } else {
            $string .= $this->city . ($this->getCountry() instanceof Country ? ", " . $this->getCountry()->getCode() : "") . ' ' . $this->zipcode;
        }

        return $string;
    }

    /**
     * Allows cloning of this class
     * @codeCoverageIgnore
     */
    public function __clone() {
        if ($this->id) {
            $this->id = null;
        }
    }

    /**
     * Determines if the class is a valid address
     *
     * @return boolean
     */
    public function isValid() {
        if ($this->street === null || $this->city === null || $this->country === null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Set the value of Boolean to determine if the address is a residental address
     *
     * @param boolean $value residential
     *
     * @return self
     */
    public function setResidential($value) {
        $this->residential = $value;

        return $this;
    }

    /**
     * Get residential
     *
     * @return boolean
     */
    public function isResidential() {
        return $this->residential;
    }

    /**
     * Determines if a state is valid based on the current country
     *
     * @param ExecutionContextInterface $context The form context
     * @return boolean
     */
    public function isStateValid(ExecutionContextInterface $context) {
        if ($this->getCountry() !== null) {
            if (($this->getCountry()->getCode() === "US" || $this->getCountry()->getCode() === "CA") && $this->getState() === null) {
                $context->addViolationAt("state", "Please select a state");
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Determines if a zipcode is valid based on the current country
     *
     * @param ExecutionContextInterface $context The form context
     * @return boolean
     */
    public function isZipcodeValid(ExecutionContextInterface $context) {
        if ($this->getCountry() !== null) {
            if ($this->getCountry()->isZipCodeRequired() && $this->getZipcode() === null) {
                $context->addViolationAt("zipcode", "Please enter a zipcode");
                return false;
            } else {
                return true;
            }
        }
    }

}
