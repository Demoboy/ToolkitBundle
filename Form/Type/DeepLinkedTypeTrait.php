<?php
/**
 * Proprietary and confidential
 * Copyright (c) ReviveIT 2018 - All Rights Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @copyright 2018
 */

namespace KMJ\ToolkitBundle\Form\Type;


use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait DeepLinkedTypeTrait
{

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'query_builder_mapping' => function (QueryBuilder $qb) {
                    return $qb;
                },
                'table_alias'           => null,
                'empty_data'            => null,
            ]
        );

        $resolver->setAllowedTypes('query_builder_mapping', ['callable']);
        $resolver->setAllowedTypes('table_alias', ['string', 'null']);
    }
}