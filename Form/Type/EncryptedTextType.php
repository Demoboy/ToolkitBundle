<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\EncryptedText;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Encrypted type field that stores the content of the field as encypted, thus
 * preventing the data in the database from being compromised easily
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @since 1.1
 */
class EncryptedTextType extends TextType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer(new \KMJ\ToolkitBundle\Form\DataTransformer\EncryptedTextDataTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            "empty_data" => new EncryptedText(),
            'data_class' => 'KMJ\ToolkitBundle\Entity\EncryptedText'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function getName()
    {
        return "kmj_toolkitbundle_encryptedtext";
    }
}
