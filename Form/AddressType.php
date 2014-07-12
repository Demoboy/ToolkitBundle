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
                            'label' => "Address (line 2):",
                            "required" => false,
                        ))
                        ->add('city', null, array(
                            'label' => 'City:'
                        ))
                        ->add('country', 'entity', array(
                            'class' => 'KMJToolkitBundle:Country',
                            'label' => 'Country:',
                            'required' => true,
                            'empty_value' => 'Please select a country',
                        ))
                        ->add('zipcode', null, array(
                            'label' => 'Zipcode:',
                            "required" => !$this->includeCountry,
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
                            'label' => "Address (line 2):",
                            "required" => false,
                        ))
                        ->add('city', null, array(
                            'label' => 'City:'
                        ))
                        ->add('country', 'entity', array(
                            'class' => 'KMJToolkitBundle:Country',
                            'label' => 'Country:',
                            'required' => true,
                            'empty_value' => 'Please select a country',
                        ))
                        ->add('zipcode', null, array(
                            'label' => 'Zipcode:',
                            "required" => !$this->includeCountry,
                ));
                break;
        }

        $builder->get("country")->addEventListener(FormEvents::POST_SUBMIT, array($this, "onPostSubmit"));

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
            default:
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
        $country = null;
        $data = $event->getData();
        $form = $event->getForm();

        if ($data instanceof Address) {
            $country = $data->getCountry();
        } else {
            $country = null;
        }

        if ($country == null) {
            $form->add("state", "choice", array(
                "label" => "State:",
                "choices" => array(),
                "empty_value" => "Please select a state:",
            ));
        } else {
            $this->buildStateField($form, $country);
        }
    }

    public function buildStateField($form, $country) {
        $form->add('state', 'entity', array(
            'class' => 'KMJToolkitBundle:State',
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

    public function onPostSubmit(FormEvent $event) {
        $country = $event->getForm()->getData();
        $form = $event->getForm()->getParent();
        $this->buildStateField($form, $country);
    }

}
