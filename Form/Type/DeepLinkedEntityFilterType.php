<?php
/**
 *
 * This file is part of the BarcodeBundle
 *
 * @copyright (c) 2017, Electronic Responsible Recyclers
 *
 */
declare(strict_types = 1);
/**
 * Created by IntelliJ IDEA.
 * User: kaelin
 * Date: 4/26/17
 * Time: 11:51 AM
 */

namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\RepositoryFilter\DeepLinkedEntityFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

class DeepLinkedEntityFilterType extends AbstractType
{
    use DeepLinkedTypeTrait;

//<editor-fold desc="Getters and Setters">
    public function getParent()
    {
        return EntityType::class;
    }
//</editor-fold>

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mappingCallback = $options['query_builder_mapping'];
        $tableAlias = $options['table_alias'];

        $builder->addModelTransformer(
            new CallbackTransformer(
                function ($deepLinkedEntity) {
                    if ($deepLinkedEntity === null) {
                        return null;
                    }

                    return $deepLinkedEntity->getEntity();
                },
                function ($model) use ($mappingCallback, $tableAlias) {
                    if ($model === null || count($model) === 0) {
                        return null;
                    }

                    $deepLinkedEntity = new DeepLinkedEntityFilter();

                    $deepLinkedEntity->setEntity($model)
                        ->setMappingQbCallback($mappingCallback)
                        ->setTableAlias($tableAlias);

                    return $deepLinkedEntity;
                }
            )
        );
    }
}