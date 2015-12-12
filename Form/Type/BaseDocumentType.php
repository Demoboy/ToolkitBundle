<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Base form type for documents
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
abstract class BaseDocumentType extends AbstractType
{

    /**
     * @var boolean Should the form include an option for naming the document
     */
    private $includeName;

    /**
     * Basic constructor
     * @param boolean $includeName If true the name form field will be added
     */
    public function __construct($includeName = null)
    {
        $this->includeName = $includeName;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("include_name", true);
        $resolver->setAllowedTypes("include_name", ["bool"]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $includeName = $options['include_name'];

        if ($this->includeName !== null) {
            $includeName = $this->includeName;
        }

        $builder->add('file', 'file', array(
            /** @Desc("File") */
            "label" => "kmjtoolkit.document.form.file.label",
            'required' => false,
            /** @Desc("Please upload a valid file") */
            "invalid_message" => "kmjtoolkit.document.form.file.invalid",
        ));

        if ($includeName) {
            $builder->add('name', 'text', array(
                /** @Desc("Name") */
                "label" => "kmjtoolkit.document.form.name.label",
                'required' => false,
            ));
        }
    }
}
