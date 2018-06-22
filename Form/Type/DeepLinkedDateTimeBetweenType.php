<?php
/**
 * Proprietary and confidential
 * Copyright (c) ReviveIT 2018 - All Rights Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @copyright 2018
 */
declare(strict_types = 1);
/**
 * Created by IntelliJ IDEA.
 * User: kaelin
 * Date: 4/26/17
 * Time: 11:51 AM
 */

namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\RepositoryFilter\DateTimeBetweenFilter;
use KMJ\ToolkitBundle\RepositoryFilter\DeepLinkedDateTimeBetweenFilter;
use KMJ\ToolkitBundle\RepositoryFilter\DeepLinkedEntityFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

class DeepLinkedDateTimeBetweenType extends AbstractType
{

    use DeepLinkedTypeTrait;

//<editor-fold desc="Getters and Setters">
    public function getParent()
    {
        return DateTimeBetweenFilterType::class;
    }
//</editor-fold>

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mappingCallback = $options['query_builder_mapping'];
        $tableAlias = $options['table_alias'];

        $builder->addModelTransformer(
            new CallbackTransformer(
                function (?DeepLinkedDateTimeBetweenFilter $deepLinkedDateTimeBetween) {
                    if ($deepLinkedDateTimeBetween === null) {
                        return null;
                    }

                    return $deepLinkedDateTimeBetween->getDates();
                },
                function (?DateTimeBetweenFilter $model) use ($mappingCallback, $tableAlias) {
                    if ($model === null) {
                        return null;
                    }

                    $deepLinkedEntity = new DeepLinkedDateTimeBetweenFilter();

                    $deepLinkedEntity->setDates($model)
                        ->setMappingQbCallback($mappingCallback)
                        ->setTableAlias($tableAlias);

                    return $deepLinkedEntity;
                }
            )
        );
    }
}