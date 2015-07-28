<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            /** @Desc("File") */
            "label" => "kmjtoolkit.document.form.file.label",
            'required' => false,
            /** @Desc("Please upload a valid file") */
            "invalid_message" => "kmjtoolkit.document.form.file.invalid",
        ));

        if ($this->includeName) {
            $builder->add('name', 'text', array(
                /** @Desc("Name") */
                "label" => "kmjtoolkit.document.form.name.label",
                'required' => false,
                "constraints" => array(
                    new NotBlank(array("message" => "kmjtoolkit.document.form.name.blank")),
                ),
            ));
        }
    }

}
