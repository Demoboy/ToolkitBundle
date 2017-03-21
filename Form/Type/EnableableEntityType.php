<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2017, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnableableEntityType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('e');

                $qb->andWhere($qb->expr()->eq('e.enabled', true));

                return $qb;
            },
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
