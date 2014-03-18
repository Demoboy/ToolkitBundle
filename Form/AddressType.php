<?php

namespace KMJ\ToolkitBundle\Form;

use KMJ\ToolkitBundle\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddressType extends AbstractType {

    const SIMPLE = 1;
    const FULL = 2;

    protected $includeCountry;
    protected $type;

    function __construct($type, $includeCountry = true) {
        $this->includeCountry = $includeCountry;
        $this->type = $type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        // initialize country to null if the order is unable to pull the address information
        // key relationship may be damaged from cloning the original database

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));

        switch ($this->type) {
            case self::SIMPLE:
                $builder
                        ->add('address', null, array(
                            'label' => 'Address:'
                        ))
                        ->add('address2', null, array(
                            'label' => "Address (line 2):"
                        ))
                        ->add('city', null, array(
                            'label' => 'City:'
                        ))
                        ->add('country', 'entity', array(
                            'class' => 'NationwideUserBundle:Country',
                            'label' => 'Country:',
                            'required' => true,
                            'empty_value' => 'Please select a country',
                        ))
                        ->add('zipcode', null, array(
                            'label' => 'Zipcode:'
                ));
                break;
            case self::FULL:
                $builder
                        ->add('firstName', null, array(
                            'label' => "First Name:"
                        ))
                        ->add('lastName', null, array(
                            'label' => 'Last Name:'
                        ))
                        ->add('companyName', null, array(
                            'label' => 'Company Name:'
                        ))
                        ->add('phoneNumber', null, array(
                            'label' => 'Phone Number:'
                        ))
                        ->add('address', null, array(
                            'label' => 'Address:'
                        ))
                        ->add('address2', null, array(
                            'label' => "Address (line 2):"
                        ))
                        ->add('city', null, array(
                            'label' => 'City:'
                        ))
                        ->add('country', 'entity', array(
                            'class' => 'NationwideUserBundle:Country',
                            'label' => 'Country:',
                            'required' => true,
                            'empty_value' => 'Please select a country',
                        ))
                        ->add('zipcode', null, array(
                            'label' => 'Zipcode:'
                ));
                break;
        }

        if (!$this->includeCountry) {
            $builder->remove("country");
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        switch ($this->type) {
            case self::SIMPLE:
                $validation = array("simple");
                break;
            case self::FULL:
                $validation = array("full");
                break;
        }

        $resolver->setDefaults(array(
            "validation_groups" => $validation,
            'data_class' => 'KMJ\ToolkitBundle\Entity\Address'
        ));
    }

    public function getName() {
        return "kmj_toolkit_address";
    }

    public function onPreSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if ($data instanceof Address) {
            $country = $data->getCountry();
        } else {
            $country = null;
        }

        $form->add('state', 'entity', array(
            'class' => 'NationwideUserBundle:State',
            'empty_value' => 'Please select a state',
            'required' => false,
            'query_builder' => function ($repository) use ($country) {
        $queryBuilder = $repository->createQueryBuilder('s');

        if ($country != null) {
            $queryBuilder->where('s.country = :country')
                    ->setParameter("country", $country);
        }

        return $queryBuilder;
    },
            'read_only' => false,
            'label' => 'State/Providence:',
        ));
    }

}
