<?php

namespace KMJ\ToolkitBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation\FormType;

/**
 * @FormType("kmj_toolkit_usertype")
 */
class UserType extends BaseType {

    public function __construct() {
        parent::__construct($this);
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstName', null, array(
                    "label" => "First Name:",
                ))
                ->add('lastName', null, array(
                    "label" => "Last Name:",
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

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'KMJ\ToolkitBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'kmj_toolkitbundle_usertype';
    }

}
