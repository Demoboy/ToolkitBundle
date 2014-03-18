<?php

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * KMJ\ToolkitBundle\Entity\Address
 *
 * @ORM\Table(name="kmj_toolkit_addresses")
 * @ORM\Entity()
 * @Assert\Callback(methods={"isZipcodeValid"})
 * @Assert\Callback(methods={"isStateValid"})
 */
class Address {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="firstName", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Please enter a first name")
     * @var string $firstName
     */
    protected $firstName;

    /**
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Please enter a last name")
     * @var string $lastName 
     */
    protected $lastName;

    /**
     * @var string $address
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
     * @var string $address2
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    protected $address2;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Please enter a city")
     */
    protected $city;

    /**
     * @var string $state
     *
     * @ORM\ManyToOne(targetEntity="State", fetch="EAGER")
     * @ORM\JoinColumn(name="stateID", referencedColumnName="id", nullable=true)
     */
    protected $state;

    /**
     * @var string $country
     *
     * @ORM\ManyToOne(targetEntity="Country",  fetch="EAGER")
     * @ORM\JoinColumn(name="countryID", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank(message="Please select a country")
     */
    protected $country;

    /**
     * @var string $zipcode
     *
     * @ORM\Column(name="zipcode", type="string", length=12, nullable=true)
     */
    protected $zipcode;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="phoneNumber", type="phone_number", nullable=true)
     * @var string $phoneNumber 
     * @AssertPhoneNumber(defaultRegion="GB")
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(name="companyName", type="string", length=255, nullable=true)
     * @var string $companyName 
     */
    protected $companyName;

    /**
     * @var decimal $longitude
     *
     * @ORM\Column(name="longitude", type="decimal", scale=7, precision=10, nullable=true)
     */
    protected $longitude;

    /**
     * @var decimal $latutude
     *
     * @ORM\Column(name="latitude", type="decimal", scale=7, precision=10, nullable=true)
     */
    protected $latitude;

    /**
     *
     * @ORM\Column(name="isResidential", type="boolean")
     */
    protected $isResidential;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address) {
        if ($address != $this->address) {
            $this->resetGeoCoordinates();
        }
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set address2
     *
     * @param string $address2
     */
    public function setAddress2($address2) {
        if ($address2 != $this->address2) {
            $this->resetGeoCoordinates();
        }
        $this->address2 = $address2;
        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2() {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     */
    public function setCity($city) {
        if ($city != $this->city) {
            $this->resetGeoCoordinates();
        }
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     */
    public function setState($state) {
        if ($state != $this->state) {
            $this->resetGeoCoordinates();
        }
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country) {
        if ($country != $this->country) {
            $this->resetGeoCoordinates();
        }
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     */
    public function setZipcode($zipcode) {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    public function __construct() {
        $this->name = "Default";
        $this->isResidential = true;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
        return $this;
    }

    public function __toString() {
        $string = $this->firstName . ' ' . $this->lastName . '<br />';
        $string .= $this->address . '<br />';

        if ($this->address2 != "") {
            $string .= $this->address2 . '<br />';
        }

        if ($this->getState() != null) {
            $string .= $this->city . ', ' . $this->getState()->getCode() . ' ' . $this->getCountry()->getCode() . ' ' . $this->zipcode;
        } else {
            $string .= $this->city . ' ' . $this->getCountry()->getCode() . ' ' . $this->zipcode;
        }

        return $string;
    }

    public function __clone() {
        if ($this->id) {
            $this->id = null;
        }
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
        return $this;
    }

    public function setGeoCoordinates($coordinates) {
        if (isset($coordinates['lat']) && isset($coordinates['lng'])) {
            $this->latitude = $coordinates['lat'];
            $this->longitude = $coordinates['lng'];
        } else {
            throw new \InvalidArgumentException;
        }

        return $this;
    }

    private function resetGeoCoordinates() {
        $this->latitude = null;
        $this->longitude = null;
    }

    public function isValid() {
        if ($this->address == "" || $this->city == "" || $this->country == "") {
            return false;
        } else {
            return true;
        }
    }

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

    public function isStateValid(ExecutionContext $context) {
        $propertyPath = $context->getPropertyPath() . '.state';

        if ($this->getCountry() != null) {
            if (($this->getCountry()->getCode() == "US" || $this->getCountry()->getCode() == "CA") && $this->getState() == null) {
                $context->setPropertyPath($propertyPath);
                $context->addViolation('Please select a state', array(), null);
                return FALSE;
            }
        }
    }

    public function isZipcodeValid(ExecutionContext $context) {
        if ($this->getCountry() != null) {
            if ($this->getCountry()->getZipCodeRequired() && $this->getZipcode() == null) {
                $propertyPath = $context->getPropertyPath() . '.zipcode';
                $context->setPropertyPath($propertyPath);
                $context->addViolation('Please enter a zipcode', array(), null);
                return FALSE;
            }
        }
    }

    public function getIsResidential() {
        return $this->isResidential;
    }

    public function setIsResidential($isResidential) {
        $this->isResidential = $isResidential;
        return $this;
    }

}
