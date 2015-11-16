<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\EncryptedDocument;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type for encrypted documents
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class EncryptedDocumentType extends BaseDocumentType
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
            "empty_data" => new EncryptedDocument(),
            'data_class' => 'KMJ\ToolkitBundle\Entity\EncryptedDocument'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kmj_toolkit_encrypteddocument';
    }
}
