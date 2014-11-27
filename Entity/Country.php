<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity class that holds country information.
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @ORM\Table(name="kmj_toolkit_countries")
 * @ORM\Entity
 */
class Country {

    /**
     * Id
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name of the country
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * Two letter country code
     *
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2)
     */
    protected $code;

    /**
     * Determines if a zipcode is required for the country. True if it is needed.
     *
     * @var boolean
     *
     * @ORM\Column(name="zipCodeRequired", type="boolean")
     */
    protected $zipCodeRequired;

    /**
     * States in the country
     *
     * @ORM\OneToMany(targetEntity="State", mappedBy="country")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $states;

    /**
     * Basic constructor
     */
    public function __construct() {
        $this->states = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Translates the class into a string
     * @return string
     */
    public function __toString() {
        return $this->getName();
    }

    /**
     * Get the value of Id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the value of Name of the country
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the value of Name of the country
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
     * Get the value of Two letter country code
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set the value of Two letter country code
     *
     * @param string $value code
     *
     * @return self
     */
    public function setCode($value) {
        $this->code = $value;

        return $this;
    }

    /**
     * Get the value of Determines if a zipcode is required for the country. True if it is needed.
     *
     * @return boolean
     */
    public function getZipCodeRequired() {
        return $this->zipCodeRequired;
    }

    /**
     * Set the value of Determines if a zipcode is required for the country. True if it is needed.
     *
     * @param boolean $value zipCodeRequired
     *
     * @return self
     */
    public function setZipCodeRequired($value) {
        $this->zipCodeRequired = $value;

        return $this;
    }

    /**
     * Get the value of States in the country
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getStates() {
        return $this->states;
    }

    /**
     * Set the value of States in the country
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $value states
     *
     * @return self
     */
    public function setStates(\Doctrine\Common\Collections\ArrayCollection $value) {
        $this->states = $value;

        return $this;
    }

}
