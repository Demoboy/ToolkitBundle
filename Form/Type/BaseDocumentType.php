<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base form type for documents.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since  1.1
 */
abstract class BaseDocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'include_name' => true,
                'file_label' => 'kmjtoolkit.document.form.file.label',
                'name_label' => 'kmjtoolkit.document.form.name.label',
            ]
        );
        
        $resolver->setAllowedTypes('include_name', ['bool']);
        $resolver->setAllowedTypes('file_label', ['string']);
        $resolver->setAllowedTypes('name_label', ['string']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $includeName = $options['include_name'];

        $builder->add(
            'file',
            FileType::class,
            [
                'label'           => $options['file_label'],
                'required'        => $options['required'],
                'invalid_message' => 'kmjtoolkit.document.form.file.invalid',
            ]
        );

        if ($includeName) {
            $builder->add(
                'name',
                TextType::class,
                [
                    'label'    => $options['name_label'],
                    'required' => $options['required'],
                ]
            );
        }
    }
}
