<?php

namespace KMJ\ToolkitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields="email", message="That email is already in use. Please enter another one")
 */
abstract class User extends BaseUser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="KMJ\ToolkitBundle\Entity\Role")
     * @ORM\OrderBy({"displayName" = "ASC"})
     * @ORM\JoinTable(name="kmj_user_roles",
     *                  joinColumns={@ORM\JoinColumn(name="userID", referencedColumnName="id")},
     *                  inverseJoinColumns={@ORM\JoinColumn(name="roleID", referencedColumnName="id")}
     *              )
     */
    protected $userRoles;

    /**
     *
     * @ORM\Column(name="firstName", type="string", length=75, nullable=true)
     */
    protected $firstName;

    /**
     *
     * @ORM\Column(name="lastName", type="string", length=75, nullable=true)
     */
    protected $lastName;

    /**
     *
     * @ORM\Column(name="passwordReset", type="boolean")
     */
    protected $passwordReset;

    public function addRole($role) {
        //make sure user doesn't already have the role        
        if (!$this->hasRole($role)) {
            $this->userRoles->add($role);
        }
        
        return $this;
    }

    public function getRoles() {
        return $this->userRoles->toArray();
    }

    public function hasRole($role) {
        foreach ($this->userRoles as $userRole) {
            if ($userRole == $role) {
                return true;
            }
        }

        return false;
    }

    public function hasRoleByName($role) {
        foreach ($this->userRoles as $userRole) {
            if ($userRole->getName() == $role) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function __construct() {
        parent::__construct();
        $this->userRoles = new ArrayCollection();
        $this->assignedLocations = new ArrayCollection();
        $this->setPasswordReset(false);
    }

    public function getUserRoles() {
        return $this->userRoles;
    }

    public function setUserRoles($userRoles) {
        $this->userRoles = $userRoles;
        return $this;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        $this->buildUsername();
        return $this;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        $this->buildUsername();
        return $this;
    }

    public function __toString() {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function buildUsername() {
        if ($this->firstName != "" && $this->lastName != "") {
            $this->username = md5($this->firstName . $this->lastName . time());
        }
    }

    public function getPasswordReset() {
        return $this->passwordReset;
    }

    public function setPasswordReset($passwordReset) {
        $this->passwordReset = $passwordReset;
        return $this;
    }

    public function isPasswordReset() {
        return $this->getPasswordReset();
    }

}
