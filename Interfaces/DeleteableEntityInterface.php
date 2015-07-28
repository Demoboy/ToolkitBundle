<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Interfaces;

/**
 * Interace for deleting an entity from the db
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
interface DeleteableEntityInterface {

    /**
     * Creates an array of all related entities to be removed
     * 
     * @return array Collection of entities that need to be removed when the parent entity is removed
     */
    public function removeRelated();
}
