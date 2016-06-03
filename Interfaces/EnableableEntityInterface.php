<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Interfaces;

/**
 * Interface for deleting an entity from the db.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since 1.2
 */
interface EnableableEntityInterface
{

    /**
     * Determines id the entity is enabled
     * @return boolean
     */
    public function isEnabled();

    /**
     * Sets the enabled var
     * 
     * @param boolean $enabled
     */
    public function setEnabled($enabled);
    
    /**
     * Determines if the entity is disabled
     * @return boolean
     */
    public function isDisabled();
}
