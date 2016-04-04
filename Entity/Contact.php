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
use KMJ\ToolkitBundle\Entity\Address;

/**
 * Entity that handles Contacts
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @ORM\Table(name="kmj_toolkit_contacts")
 * @ORM\Entity()
 */
class Contact
{
    /**
     * id for the contact
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The first name for the contact
     *
     * @ORM\Column(name="firstName", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="kmjtoolkit.contact.firstname.validation.notblank.message")
     * @var string
     */
    protected $firstName;

    /**
     * The last name for the contact
     *
     * @ORM\Column(name="lastName", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="kmjtoolkit.contact.lastname.validation.notblank.message")
     * @var string
     */
    protected $lastName;

    /**
     * The company name for the contact
     * @ORM\Column(name="companyName", type="string", length=255, nullable=true)
     * @var string
     */
    protected $companyName;

    /**
     * The phone number for the contact
     *
     * @ORM\Column(name="phoneNumber", type="phone_number", nullable=true)
     * @var PhoneNumber
     * @AssertPhoneNumber(defaultRegion="US")
     */
    protected $phoneNumber;

    /**
     * The email address for the contact
     *
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * The address of the contact
     * @ORM\ManyToOne(targetEntity="Address", cascade={"all"})
     * @var Address
     */
    protected $address;

    /**
     * Get the value of id for the contact
     * @codeCoverageIgnore
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of The first name for the contact
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of The first name for the contact
     *
     * @param string $value firstName
     *
     * @return self
     */
    public function setFirstName($value)
    {
        $this->firstName = $value;

        return $this;
    }

    /**
     * Get the value of The last name for the contact
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of The last name for the contact
     *
     * @param string $value lastName
     *
     * @return self
     */
    public function setLastName($value)
    {
        $this->lastName = $value;

        return $this;
    }

    /**
     * Get the value of The phone number for the contact
     *
     * @return PhoneNumber
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set the value of The phone number for the contact
     *
     * @param PhoneNumber $value phoneNumber
     *
     * @return self
     */
    public function setPhoneNumber(PhoneNumber $value = null)
    {
        $this->phoneNumber = $value;

        return $this;
    }

    /**
     * Get the value of The company name for the contact
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set the value of The company name for the contact
     *
     * @param string $value companyName
     *
     * @return self
     */
    public function setCompanyName($value)
    {
        $this->companyName = $value;

        return $this;
    }

    /**
     * Translates the contact into a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFirstName()." ".$this->getLastName();
    }

    /**
     * Allows cloning of this class
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
            $this->address = clone $this->address;
        }
    }

    /**
     * Get the value of The address of the contact
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of The address of the contact
     *
     * @param Address address
     *
     * @return self
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of The email address for the contact
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of The email address for the contact
     *
     * @param string email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}