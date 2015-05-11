<?php

namespace KMJ\ToolkitBundle\Form\Type;

use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType {

    /**
     * Should address form include country
     * 
     * @var boolean
     */
    private $includeCountry;

    /**
     * Should the address be required
     * @var boolean
     */
    private $addressRequired;

    /**
     * Should the address form be shown
     * @var boolean 
     */
    private $includeAddress;

    /**
     * Basic constructor
     * @param boolean $includeCountry Should address form include country
     * @param boolean $addressRequired Should address be required
     */
    public function __construct($includeAddress = true, $includeCountry = true, $addressRequired = true) {
        $this->includeCountry = $includeCountry;
        $this->addressRequired = $addressRequired;
        $this->includeAddress = $includeAddress;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstName', null, array(
                    'label' => "First Name",
                ))
                ->add('lastName', null, array(
                    'label' => 'Last Name',
                ))
                ->add('companyName', null, array(
                    'label' => 'Company Name',
                    "required" => false,
                ))
                ->add('phoneNumber', "tel", array(
                    'default_region' => 'US',
                    'format' => PhoneNumberFormat::NATIONAL,
                    'label' => 'Phone Number',
                    "required" => false,
        ));

        if ($this->includeAddress) {
            $builder->add('address', new AddressType($this->includeCountry, $this->addressRequired));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'KMJ\ToolkitBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'kmj_toolkitbundle_contact';
    }

}
