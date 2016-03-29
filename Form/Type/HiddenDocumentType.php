<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\HiddenDocument;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for HiddenDocument
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class HiddenDocumentType extends BaseDocumentType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "empty_data" => new HiddenDocument(),
            'data_class' => HiddenDocument::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'kmj_toolkit_hiddendocument';
    }
}
