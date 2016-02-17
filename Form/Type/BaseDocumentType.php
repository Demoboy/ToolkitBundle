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

        $builder->add('file', \Symfony\Component\Form\Extension\Core\Type\FileType::class, array(
            /** @Desc("File") */
            "label" => "kmjtoolkit.document.form.file.label",
            'required' => false,
            /** @Desc("Please upload a valid file") */
            "invalid_message" => "kmjtoolkit.document.form.file.invalid",
        ));

        if ($includeName) {
            $builder->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                /** @Desc("Name") */
                "label" => "kmjtoolkit.document.form.name.label",
                'required' => false,
            ));
        }
    }
}
