<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\WebDocument;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for web documents.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since 1.1
 */
class WebDocumentType extends BaseDocumentType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'empty_data' => new WebDocument(),
            'data_class' => WebDocument::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'kmj_toolkit_webdocument';
    }
}
