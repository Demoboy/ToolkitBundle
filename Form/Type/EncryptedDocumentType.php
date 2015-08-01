<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

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
        $resolver->setDefaults(array(
            "empty_data" => new \KMJ\ToolkitBundle\Entity\EncryptedDocument(),
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
