<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2015, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form type for note entity.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 *
 * @since  1.1
 */
abstract class BaseNoteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'text',
            TextareaType::class,
            [
                'label' => 'kmjtoolkit.note.form.text.label',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'kmj_toolkitbundle_basenote';
    }
}
