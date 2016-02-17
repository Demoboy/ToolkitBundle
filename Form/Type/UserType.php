<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use KMJ\ToolkitBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;

/**
 * User form for user entity
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
        $builder->add('firstName', null, array(
                /** @Desc("First Name") */
                "label" => "kmjtoolkit.user.form.firstname.label",
            ))
            ->add('lastName', null, array(
                /** @Desc("Last Name") */
                "label" => "kmjtoolkit.user.form.lastname.label",
        ));

        parent::buildForm($builder, $options);

        $builder->remove("email");
        $builder->add('email', EmailType::class, array(
            'label' => 'form.email',
            'translation_domain' => 'FOSUserBundle',
            'constraints' => array(
                new Email(array(
                    "groups" => array("simple"),
                    "checkMX" => true,
                    ))
            )
        ));

        $builder->remove('username');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

    public function getParent()
    {
        return \FOS\UserBundle\Form\Type\RegistrationFormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kmj_toolkitbundle_usertype';
    }
}
