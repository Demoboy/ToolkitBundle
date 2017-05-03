<?php
/**
 * This file is part of the BarcodeBundle.
 *
 * @copyright (c) 2017, Electronic Responsible Recyclers
 */

namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\S3Document;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for encrypted documents.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since  1.2
 */
class S3DocumentType extends BaseDocumentType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'data_class' => S3Document::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'kmj_toolkit_s3document';
    }
}
