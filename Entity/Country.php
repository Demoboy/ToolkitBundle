<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use KMJ\ToolkitBundle\Traits\JsonSerializableTrait;

/**
 * Entity class that holds country information.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @ORM\Table(name="kmj_toolkit_countries")
 * @ORM\Entity
 */
class Country implements JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * Id.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name of the country.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * Two letter country code.
     *
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2)
     */
    protected $code;

    /**
     * Determines if a zipcode is required for the country. True if it is needed.
     *
     * @var bool
     *
     * @ORM\Column(name="zipCodeRequired", type="boolean")
     */
    protected $zipCodeRequired;

    /**
     * States in the country.
     *
     * @ORM\OneToMany(targetEntity="State", mappedBy="country")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $states;

    /**
     * Basic constructor.
     */
    public function __construct()
    {
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
        $this->zipCodeRequired = false;
    }

    /**
     * Translates the class into a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the value of Name of the country.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name of the country.
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
     * Get the value of Id.
     *
     * @codeCoverageIgnore
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of Two letter country code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of Two letter country code.
     *
     * @param string $value code
     *
     * @return self
     */
    public function setCode($value)
    {
        $this->code = $value;

        return $this;
    }

    /**
     * Get the value of Determines if a zipcode is required for the country. True if it is needed.
     *
     * @return bool
     */
    public function isZipCodeRequired()
    {
        return $this->zipCodeRequired;
    }

    /**
     * Set the value of Determines if a zipcode is required for the country. True if it is needed.
     *
     * @param bool $value zipCodeRequired
     *
     * @return self
     */
    public function setZipCodeRequired($value)
    {
        $this->zipCodeRequired = $value;

        return $this;
    }

    /**
     * Get the value of States in the country.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getStates()
    {
        return $this->states;
    }
}
