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
 * Entity class that holds information about states.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @ORM\Table(name="kmj_toolkit_states")
 * @ORM\Entity
 */
class State implements JsonSerializable
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
     * Name of the state.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    protected $name;

    /**
     * Two letter state code.
     *
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2, nullable=true)
     */
    protected $code;

    /**
     * Country to which the state belongs to.
     *
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="states")
     * @ORM\JoinColumn(name="countryID", referencedColumnName="id")
     */
    protected $country;

    /**
     * Translates the state into a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
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
     * Get the value of Name of the state.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name of the state.
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
     * Get the value of Two letter state code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of Two letter state code.
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
     * Get the value of Country to which the state belongs to.
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of Country to which the state belongs to.
     *
     * @param Country $value country
     *
     * @return self
     */
    public function setCountry(Country $value)
    {
        $this->country = $value;

        return $this;
    }
}
