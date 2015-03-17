<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class EncryptedDocumentType extends BaseDocumentType {

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            "empty_data" => new \KMJ\ToolkitBundle\Entity\EncryptedDocument(),
            'data_class' => 'KMJ\ToolkitBundle\Entity\EncryptedDocument'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'kmj_toolkit_encrypteddocument';
    }

}
