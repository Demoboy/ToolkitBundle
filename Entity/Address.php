<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use KMJ\ToolkitBundle\Constraints\TranslatableCallback;
use JsonSerializable;

/**
 * Entity that handles Addresses
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @ORM\Table(name="kmj_toolkit_addresses")
 * @ORM\Entity()
 */
class Address implements JsonSerializable
{
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
     * The street address
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="kmjtoolkit.address.street.validation.blank", groups={"simple", "full"})
     * @Assert\Length(
     *          min="3", minMessage="kmjtoolkit.address.street.validation.min",
     *          max="255", maxMessage="kmjtoolkit.address.street.validation.max"
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
     * @Assert\NotBlank(message="kmjtoolkit.address.city.validation.blank", groups={"simple", "full"})
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
     * @Assert\NotBlank(message="kmjtoolkit.address.country.validation.blank")
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of The street address
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of The street address
     *
     * @param string $value street address
     *
     * @return self
     */
    public function setStreet($value)
    {
        $this->street = $value;

        return $this;
    }

    /**
     * Get the value of The street address line 2
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set the value of The street address (line 2)
     *
     * @param string $value unit
     *
     * @return self
     */
    public function setUnit($value)
    {
        $this->unit = $value;

        return $this;
    }

    /**
     * Get the value of The city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of The city
     *
     * @param string $value city
     *
     * @return self
     */
    public function setCity($value)
    {
        $this->city = $value;

        return $this;
    }

    /**
     * Get the value of The state
     *
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of The state
     *
     * @param State $value state
     *
     * @return self
     */
    public function setState(State $value = null)
    {
        $this->state = $value;

        if ($this->country === null && $value !== null) {
            $this->country = $this->state->getCountry();
        }

        return $this;
    }

    /**
     * Get the value of The country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of The country
     *
     * @param Country $value country
     *
     * @return self
     */
    public function setCountry(Country $value = null)
    {
        $this->country = $value;

        return $this;
    }

    /**
     * Get the value of The zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set the value of The zipcode
     *
     * @param string $value zipcode
     *
     * @return self
     */
    public function setZipcode($value)
    {
        $this->zipcode = $value;

        return $this;
    }

    /**
     * Get the value of The short name of the address (for address books)
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of The short name of the address (for address books)
     *
     * @param string $value name
     *
     * @return self
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Get the value of The longitude of the address
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of The longitude of the address
     *
     * @param float $value longitude
     *
     * @return self
     */
    public function setLongitude($value)
    {
        $this->longitude = $value;

        return $this;
    }

    /**
     * Get the value of The latitude of the address
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the value of The latitude of the address
     *
     * @param float $value latitude
     *
     * @return self
     */
    public function setLatitude($value)
    {
        $this->latitude = $value;

        return $this;
    }

    /**
     * Basic constructor
     */
    public function __construct()
    {
        $this->name = "Default";
    }

    /**
     * Translates the address into a string (with html formating)
     *
     * @return string
     */
    public function __toString()
    {
        $string = null;

        $string .= $this->street." ";

        if ($this->unit != "") {
            $string .= $this->unit.' ';
        }

        if ($this->getState() instanceof State) {
            $string .= $this->city.', '.$this->getState()->getCode().' '.$this->getCountry()->getCode().' '.$this->zipcode;
        } else {
            $string .= $this->city.($this->getCountry() instanceof Country ? ", ".$this->getCountry()->getCode()
                    : "").' '.$this->zipcode;
        }

        return $string;
    }

    /**
     * Allows cloning of this class
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
        }
    }

    /**
     * Determines if the class is a valid address
     *
     * @return boolean
     */
    public function isValid()
    {
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
    public function setResidential($value)
    {
        $this->residential = $value;

        return $this;
    }

    /**
     * Get residential
     *
     * @return boolean
     */
    public function isResidential()
    {
        return $this->residential;
    }

    /**
     * Determines if a state is valid based on the current country
     *
     * @param ExecutionContextInterface $context The form context
     * @return boolean
     * @TranslatableCallback(message="kmjtoolkit.address.state.validation.valid")
     */
    public function isStateValid(ExecutionContextInterface $context)
    {
        if ($this->getCountry() !== null) {
            if (($this->getCountry()->getCode() === "US" || $this->getCountry()->getCode()
                === "CA") && $this->getState() === null) {
                $context->addViolationAt("state",
                    "kmjtoolkit.address.state.validation.valid");
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
     * @TranslatableCallback(message="kmjtoolkit.address.zipcode.validation.valid")
     */
    public function isZipcodeValid(ExecutionContextInterface $context)
    {
        if ($this->getCountry() !== null) {
            if ($this->getCountry()->isZipCodeRequired() && $this->getZipcode() === null) {
                $context->addViolationAt("zipcode",
                    "kmjtoolkit.address.zipcode.validation.valid");
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            "city" => $this->city,
            "country" => ($this->country !== null ? $this->country->getName() : null),
            "state" => ($this->state !== null ? $this->state->getName() : null),
            "zipcode" => $this->zipcode,
            "street" => $this->street,
            "unit" => $this->unit,
        );
    }
}
