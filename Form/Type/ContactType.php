<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2015, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for contact entity
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class ContactType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array(
                /** @Desc("First Name") */
                "label" => "kmjtoolkit.contact.form.firstname.label",
            ))
            ->add('lastName', TextType::class, array(
                /** @Desc("Last Name") */
                "label" => "kmjtoolkit.contact.form.lastname.label",));

        if ($options['include_company']) {
            $builder->add('companyName', TextType::class, array(
                /** @Desc("Company Name") */
                "label" => "kmjtoolkit.contact.form.companyname.label",
                "required" => false,
            ));
        }
        
        $builder->add('phoneNumber', PhoneNumberType::class, array(
                /** @Desc("Phone Number") */
                "label" => "kmjtoolkit.contact.form.phonenumber.label",
                'default_region' => 'US',
                'format' => PhoneNumberFormat::NATIONAL,
                "required" => false,
            ))
            ->add("email", EmailType::class, array(
                /** @Desc("Email address") */
                "label" => "kmjtoolkit.contact.form.email.label",
                "required" => false,
        ));

        if ($options["include_address"]) {
            $builder->add('address', AddressType::class, array_merge([
                /** @Desc("Address") */
                "label" => "kmjtoolkit.contact.form.address.label",
                    ], $options['address_options']));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KMJ\ToolkitBundle\Entity\Contact',
            "include_address" => true,
            "include_company" => true,
            "address_options" => [],
        ));

        $resolver->setAllowedTypes("include_address", ["boolean"]);
        $resolver->setAllowedTypes("address_options", ["array"]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'kmj_toolkitbundle_contact';
    }
}
