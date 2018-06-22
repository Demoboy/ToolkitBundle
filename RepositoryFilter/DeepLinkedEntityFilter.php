<?php

/**
 * Created by IntelliJ IDEA.
 * User: kaelin
 * Date: 4/28/17
 * Time: 11:04 AM
 */

namespace KMJ\ToolkitBundle\RepositoryFilter;


class DeepLinkedEntityFilter extends DeepLinkedFilter
{
    /**
     * @var mixed|null
     */
    private $entity;

//<editor-fold desc="Getters and Setters">
    /**
     * @return mixed|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed|null $entity
     *
     * @return DeepLinkedEntityFilter
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
//</editor-fold>


}