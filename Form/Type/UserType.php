<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use KMJ\ToolkitBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

/**
 * User form for user entity.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @codeCoverageIgnore
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'firstName',
            null,
            [
                'label' => 'kmjtoolkit.user.form.firstname.label',
            ]
        )
            ->add(
                'lastName',
                null,
                [
                    'label' => 'kmjtoolkit.user.form.lastname.label',
                ]
            );

        parent::buildForm($builder, $options);

        $emailOptions = $builder->get('email')->getOptions();

        $builder->add(
            'email',
            EmailType::class,
            array_merge(
                $emailOptions,
                [
                    'constraints' => [
                        new Email(
                            [
                                'groups' => ['simple'],
                                'checkMX' => true,
                            ]
                        ),
                    ],
                ]
            )
        );

        $builder->remove('username');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }

    public function getParent()
    {
        return RegistrationFormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'kmj_toolkitbundle_usertype';
    }
}
