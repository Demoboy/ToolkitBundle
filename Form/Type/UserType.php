<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * User form for user entity
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @FormType("kmj_toolkit_usertype")
 * @codeCoverageIgnore
 */
class UserType extends BaseType {

    /**
     * Basic Constructor
     */
    public function __construct() {
        parent::__construct($this);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
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
        $builder->add('email', 'email', array(
            'label' => 'form.email',
            'translation_domain' => 'FOSUserBundle',
            'constraints' => array(
                new \Symfony\Component\Validator\Constraints\Email(array(
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
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'KMJ\ToolkitBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'kmj_toolkitbundle_usertype';
    }

}
