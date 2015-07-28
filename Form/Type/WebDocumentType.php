<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\WebDocument;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class WebDocumentType extends BaseDocumentType {

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            "empty_data" => new WebDocument(),
            'data_class' => 'KMJ\ToolkitBundle\Entity\WebDocument'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'kmj_toolkit_webdocument';
    }

}
