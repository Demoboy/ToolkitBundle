<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\HiddenDocument;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type for HiddenDocument
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class HiddenDocumentType extends BaseDocumentType
{

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            "empty_data" => new HiddenDocument(),
            'data_class' => 'KMJ\ToolkitBundle\Entity\HiddenDocument'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kmj_toolkit_hiddendocument';
    }
}
