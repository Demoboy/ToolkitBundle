<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
abstract class BaseDocumentType extends AbstractType {

    private $includeName;

    public function __construct($includeName = true) {
        $this->includeName = $includeName;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('file', 'file', array(
            'label' => "File",
            'required' => false,
            'invalid_message' => "Please enter a valid file",
        ));

        if ($this->includeName) {
            $builder->add('name', 'text', array(
                'label' => "Name",
                'required' => false,
                'invalid_message' => "Please enter a valid name",
            ));
        }
    }

}
