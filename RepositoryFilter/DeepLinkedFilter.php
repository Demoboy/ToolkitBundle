<?php
/**
 * Proprietary and confidential
 * Copyright (c) ReviveIT 2018 - All Rights Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @copyright 2018
 */

/**
 * Created by IntelliJ IDEA.
 * User: kaelin
 * Date: 4/28/17
 * Time: 11:04 AM
 */

namespace KMJ\ToolkitBundle\RepositoryFilter;


abstract class DeepLinkedFilter
{


    /**
     * @var callable|null
     */
    protected $mappingQbCallback;

    /**
     * @var string|null
     */
    protected $tableAlias;


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