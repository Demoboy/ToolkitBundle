<?php

namespace KMJ\ToolkitBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ClassicAirAviation\ToolkitBundle\Entity\Role
 *
 * @ORM\Table(name="kmj_toolkit_roles")
 * @ORM\Entity()
 */
class Role implements RoleInterface {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @var string $name
     */
    protected $name;

    /**
     * @ORM\Column(name="displayName", type="string", length=255)
     * @var string $name
     */
    protected $displayName;

    /**
     * @ORM\Column(type="string", length=255)
     * @var type 
     */
    protected $description;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime $createdOn
     */
    protected $createdOn;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getRole() {
        return $this->getName();
    }

    public function setName($name) {
        $name = str_replace(' ', '_', strtoupper($name));
        
        if (substr($name, 0, 5) != "ROLE_") {
            $name = 'ROLE_' . $name;
        }

        $this->name = $name;
        return $this;
    }

    function __construct() {
        $this->createdOn = new \DateTime('NOW');
    }

    public function getName() {
        return $this->name;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function __toString() {
        return $this->getDisplayName();
    }
    
    public function getDisplayName() {
        return $this->displayName;
    }

    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
        return $this;
    }
}