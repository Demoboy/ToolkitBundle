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

use KMJ\ToolkitBundle\RepositoryFilter\DateTimeBetweenFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeBetweenFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['type']) {
            case 'date':
                $type = DateType::class;
                break;
            case 'datetime':
                $type = DateTimeType::class;
                break;
        }

        $builder
            ->add(
                'start',
                $type,
                [
                    'label'    => $options['start_label'],
                    'widget'   => 'single_text',
                    'format'   => 'MM/dd/yyyy',
                    'html5'    => false,
                    'required' => $options['required'],
                    'attr'     => [
                        'class' => 'date-picker',
                    ],
                ]
            )
            ->add(
                'end',
                $type,
                [
                    'label'    => $options['end_label'],
                    'widget'   => 'single_text',
                    'format'   => 'MM/dd/yyyy',
                    'html5'    => false,
                    'required' => $options['required'],
                    'attr'     => [
                        'class' => 'date-picker',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'type'        => 'date',
                'required'    => false,
                'data_class'  => DateTimeBetweenFilter::class,
                'start_label' => 'Start date',
                'end_label'   => 'End date',
            ]
        );

        $resolver->setAllowedTypes('required', ['bool']);
        $resolver->setAllowedValues('type', ['date', 'datetime']);
    }
}