<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\S3EncryptedDocument;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type for encrypted documents
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class S3EncryptedDocumentType extends EncryptedDocumentType
{

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
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            "empty_data" => new S3EncryptedDocument(),
            'data_class' => 'KMJ\ToolkitBundle\Entity\S3EncryptedDocument'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kmj_toolkit_s3encrypteddocument';
    }
}
