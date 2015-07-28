<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait that implements HideableEntityInterface
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
trait HideableEntityTrait {

    /**
     * @var boolean determines whether or not the class should not be displayed or not
     * @ORM\Column(type="boolean")
     */
    protected $hidden = false;

    /**
     * Checks whether or not the class should be hidden
     * 
     * @return boolean True if the entity should be hidden
     */
    public function isHidden() {
        return $this->hidden;
    }

    /**
     * Sets the hidden var
     * 
     * @param boolean $hidden Whether the entity is hidden or not
     * 
     * @return self
     */
    public function setHidden($hidden) {
        $this->hidden = $hidden;
    }

}
