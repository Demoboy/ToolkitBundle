<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Entity class that handles roles for users
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @ORM\Table(name="kmj_toolkit_roles")
 * @ORM\Entity()
 */
class Role implements RoleInterface
{

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
     * Name of the role
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @var string
     */
    protected $name;

    /**
     * Display name for the role
     *
     * @ORM\Column(name="displayName", type="string", length=255)
     * @var string
     */
    protected $displayName;

    /**
     * Description of the role
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $description;

    /**
     * Date role was created on
     *
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $createdOn;

    /**
     * Parent role
     * @ORM\ManyToOne(targetEntity="Role")
     * @var Role
     */
    protected $parent;

    /**
     * {@inheritDoc}
     * @return string
     */
    public function getRole()
    {
        return $this->getName();
    }

    /**
     * Basic constructor
     */
    function __construct()
    {
        $this->createdOn = new DateTime('NOW');
    }

    /**
     * Translates role into string by using the display name
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getDisplayName();
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
     * Get the value of Name of the role
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name of the role
     *
     * @param string $name name
     *
     * @return self
     */
    public function setName($name)
    {
        $name = str_replace(' ', '_', strtoupper($name));

        if (substr($name, 0, 5) != "ROLE_") {
            $name = 'ROLE_' . $name;
        }

        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of Display name for the role
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set the value of Display name for the role
     *
     * @param string $value displayName
     *
     * @return self
     */
    public function setDisplayName($value)
    {
        $this->displayName = $value;

        return $this;
    }

    /**
     * Get the value of Description of the role
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of Description of the role
     *
     * @param string $value description
     *
     * @return self
     */
    public function setDescription($value)
    {
        $this->description = $value;

        return $this;
    }

    /**
     * Get the value of Date role was created on
     *
     * @return DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Get the value of Parent role
     *
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the value of Parent role
     *
     * @param Role $value parent
     *
     * @return self
     */
    public function setParent(Role $value)
    {
        $this->parent = $value;

        return $this;
    }
}
