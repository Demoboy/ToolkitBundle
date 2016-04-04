<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use KMJ\ToolkitBundle\Interfaces\DeleteableEntityInterface;
use KMJ\ToolkitBundle\Interfaces\HideableEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mapped superclass for a basic user. 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields="email", message="That email is already in use. Please enter another one")
 */
abstract class User extends BaseUser implements DeleteableEntityInterface, HideableEntityInterface
{

    use \KMJ\ToolkitBundle\Traits\HideableEntityTrait;
    /**
     * Id
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The roles for the user
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="KMJ\ToolkitBundle\Entity\Role")
     * @ORM\OrderBy({"displayName" = "ASC"})
     * @ORM\JoinTable(name="kmj_user_roles")
     */
    protected $userRoles;

    /**
     * The user's first name
     *
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=75, nullable=true)
     * @Assert\NotBlank(groups={"simple"})
     */
    protected $firstName;

    /**
     * The user's last name
     *
     * @var string
     * @ORM\Column(name="lastName", type="string", length=75, nullable=true)
     * @Assert\NotBlank(groups={"simple"})
     */
    protected $lastName;

    /**
     * Determines whether the user needs to reset their password. True if so.
     * @var boolean
     * @ORM\Column(name="passwordReset", type="boolean")
     */
    protected $passwordReset;

    /**
     * {@inheritDoc}
     *
     * @param mixed $role The role to add
     * @return self
     */
    public function addRole($role)
    {
        //make sure user doesn't already have the role
        if (!$this->hasRole($role)) {
            $this->userRoles->add($role);
        }

        return $this;
    }

    /**
     * Removes a users role by role
     * @param Role $role
     */
    public function removeUserRole(Role $role)
    {
        foreach ($this->userRoles as $k => $r) {
            if ($r->getId() === $role->getId()) {
                $this->userRoles->remove($k);
            }
        }
    }

    /**
     * Removes a users role by name
     * @param string $role
     */
    public function removeUserRoleByName($role)
    {
        foreach ($this->userRoles as $key => $r) {
            if ($r->getName() === $role) {
                $this->userRoles->remove($key);
            }
        }
    }

    /**
     * Gets the user roles as an array
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->userRoles->toArray();
    }

    /**
     * Determines if a user has a specified role
     * @param mixed $role The role to check against
     * @return boolean
     */
    public function hasRole($role)
    {
        foreach ($this->userRoles as $userRole) {
            if ($userRole === $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines if user has a specified role
     * by comparing role names
     *
     * @param string $role The role name to check against
     * @return boolean
     */
    public function hasRoleByName($role)
    {
        foreach ($this->userRoles as $userRole) {
            if ($userRole->getName() === $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * Basic constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRoles = new ArrayCollection();
        $this->assignedLocations = new ArrayCollection();
        $this->setPasswordReset(false);
    }

    /**
     * Translates the user into a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * Builds a random but unique username
     */
    public function buildUsername()
    {
        if ($this->firstName != "" && $this->lastName != "") {
            $this->username = md5($this->firstName.$this->lastName.time());
        }
    }

    /**
     * Does the user need to reset password
     *
     * @return boolean
     */
    public function isPasswordReset()
    {
        return $this->getPasswordReset();
    }

    /**
     * Get the value of Id
     * @codeCoverageIgnore
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of The roles for the user
     *
     * @return ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Get the value of The user's first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of The user's first name
     *
     * @param string $value firstName
     *
     * @return self
     */
    public function setFirstName($value)
    {
        $this->firstName = $value;
        $this->buildUsername();

        return $this;
    }

    /**
     * Get the value of The user's last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of The user's last name
     *
     * @param string $value lastName
     *
     * @return self
     */
    public function setLastName($value)
    {
        $this->lastName = $value;
        $this->buildUsername();

        return $this;
    }

    /**
     * Get the value of Determines whether the user needs to reset their password. True if so.
     *
     * @return boolean
     */
    public function getPasswordReset()
    {
        return $this->passwordReset;
    }

    /**
     * Set the value of Determines whether the user needs to reset their password. True if so.
     *
     * @param boolean $value passwordReset
     *
     * @return self
     */
    public function setPasswordReset($value)
    {
        $this->passwordReset = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRelated()
    {
        return array();
    }

    /**
     * Sets the user roles
     * 
     * @param ArrayCollection $userRoles
     * @return \KMJ\ToolkitBundle\Entity\User
     */
    public function setUserRoles($userRoles)
    {
        $this->userRoles = $userRoles;
        return $this;
    }
}