<?php
/**
 *
 * This file is part of the BarcodeBundle
 *
 * @copyright (c) 2017, Electronic Responsible Recyclers
 *
 */

/**
 * Created by IntelliJ IDEA.
 * User: kaelin
 * Date: 4/28/17
 * Time: 11:04 AM
 */

namespace KMJ\ToolkitBundle\RepositoryFilter;


class DeepLinkedEntityFilter
{
    /**
     * @var mixed|null
     */
    private $entity;

    /**
     * @var callable|null
     */
    private $mappingQbCallback;

    /**
     * @var string|null
     */
    private $tableAlias;

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

    /**
     * @return callable|null
     */
    public function getMappingQbCallback()
    {
        return $this->mappingQbCallback;
    }

    /**
     * @param callable|null $mappingQbCallback
     *
     * @return DeepLinkedEntityFilter
     */
    public function setMappingQbCallback($mappingQbCallback)
    {
        $this->mappingQbCallback = $mappingQbCallback;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTableAlias()
    {
        return $this->tableAlias;
    }

    /**
     * @param null|string $tableAlias
     *
     * @return DeepLinkedEntityFilter
     */
    public function setTableAlias($tableAlias)
    {
        $this->tableAlias = $tableAlias;

        return $this;
    }


}