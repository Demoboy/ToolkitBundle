<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\HiddenDocument;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class HiddenDocumentType extends BaseDocumentType {

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            "empty_data" => new HiddenDocument(),
            'data_class' => 'KMJ\ToolkitBundle\Entity\HiddenDocument'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'kmj_toolkit_hiddendocument';
    }

}
