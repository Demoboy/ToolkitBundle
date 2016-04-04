<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Interfaces;

/**
 * Interace for hiding an entity from being displayed
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
interface HideableEntityInterface
{

    /**
     * Checks whether or not the class should be hidden
     * 
     * @return boolean True if the entity should be hidden
     */
    public function isHidden();

    /**
     * Sets the hidden var
     * 
     * @param boolean $hidden Whether the entity is hidden or not
     * 
     * @return self
     */
    public function setHidden($hidden);
}