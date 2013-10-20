<?php

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KMJ\ToolkitBundle\Entity\Country
 *
 * @ORM\Table(name="kmj_toolkit_countries")
 * @ORM\Entity
 */
class Country {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=2)
     */
    protected $code;

    /**
     * @var boolean $zipCodeRequired
     *
     * @ORM\Column(name="zipCodeRequired", type="boolean")
     */
    protected $zipCodeRequired;
    
    /**
     * @ORM\OneToMany(targetEntity="State", mappedBy="country")
     * @var type 
     */
    protected $states;
        
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set zipCodeRequired
     *
     * @param boolean $zipCodeRequired
     */
    public function setZipCodeRequired($zipCodeRequired) {
        $this->zipCodeRequired = $zipCodeRequired;
    }

    /**
     * Get zipCodeRequired
     *
     * @return boolean 
     */
    public function getZipCodeRequired() {
        return $this->zipCodeRequired;
    }
    
    public function __toString() {
        return $this->getName();
    }
    
    public function getStates() {
        return $this->states;
    }

    public function setStates($states) {
        $this->states = $states;
    }


}