<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use KMJ\ToolkitBundle\Entity\Address;
use KMJ\ToolkitBundle\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/**
 * Address form for address entity
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @codeCoverageIgnore
 */
class AddressType extends AbstractType {

    /**
     * Constant for the simple form
     */
    const SIMPLE = "simple";

    /**
     * Constant fot the full form
     */
    const FULL = "full";

    /**
     * Should the form include the country
     * 
     * @var boolean
     */
    protected $includeCountry;

    /**
     * The type of form to be used
     * 
     * @var int
     */
    protected $type;

    /**
     * Should the fields be marked as required
     *
     * @var boolean 
     */
    protected $required;

    /**
     * Basic constructor
     * 
     * @param int $type The type of form to use
     * @param boolean $includeCountry Should the form include a country dropdown
     * @param boolean $required Should fields be marked as required
     */
    function __construct($type, $includeCountry = true, $required = true) {
        if ($type != self::SIMPLE && $type != self::FULL) {
            throw new InvalidArgumentException("Type {$type} is unknown");
        }
        
        $this->includeCountry = $includeCountry;
        $this->type = $type;
        $this->required = $required;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        // initialize country to null if the order is unable to pull the address information
        // key relationship may be damaged from cloning the original database

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));

        switch ($this->type) {
            case self::SIMPLE:
                $builder
                        ->add('address', null, array(
                            'label' => 'Address:',
                            "required" => $this->required,
                        ))
                        ->add('address2', null, array(
                            'label' => "Address (line 2):",
                            "required" => false,
                        ))
                        ->add('city', null, array(
                            'label' => 'City:',
                            "required" => $this->required,
                        ))
                        ->add('country', 'entity', array(
                            'class' => 'KMJToolkitBundle:Country',
                            'label' => 'Country:',
                            "required" => $this->required,
                            'empty_value' => 'Please select a country',
                        ))
                        ->add('zipcode', null, array(
                            'label' => 'Zipcode:',
                            "required" => (!$this->includeCountry || $this->required) ? false : true,
                ));
                break;
            case self::FULL:
                $builder
                        ->add('firstName', null, array(
                            'label' => "First Name:",
                            "required" => $this->required,
                        ))
                        ->add('lastName', null, array(
                            'label' => 'Last Name:',
                            "required" => $this->required,
                        ))
                        ->add('companyName', null, array(
                            'label' => 'Company Name:',
                            "required" => false,
                        ))
                        ->add('phoneNumber', null, array(
                            'label' => 'Phone Number:',
                            "required" => false,
                        ))
                        ->add('address', null, array(
                            'label' => 'Address:',
                            "required" => $this->required,
                        ))
                        ->add('address2', null, array(
                            'label' => "Address (line 2):",
                            "required" => false,
                        ))
                        ->add('city', null, array(
                            'label' => 'City:',
                            "required" => $this->required,
                        ))
                        ->add('country', 'entity', array(
                            'class' => 'KMJToolkitBundle:Country',
                            'label' => 'Country:',
                            "required" => $this->required,
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return "kmj_toolkit_address";
    }

    /**
     * {@inheritdoc}
     */
    public function onPreSetData(FormEvent $event) {
        $country = null;
        $data = $event->getData();
        $form = $event->getForm();

        if ($data instanceof Address) {
            $country = $data->getCountry();
        } else {
            $country = null;
        }

        if ($country === null) {
            $form->add("state", "choice", array(
                "label" => "State:",
                "choices" => array(),
                "empty_value" => "Please select a state:",
            ));
        } else {
            $this->buildStateField($form, $country);
        }
    }

    /**
     * Builds the state field
     * 
     * @param Form $form The form to add the field to
     * @param Country $country The country to add the states of
     */
    public function buildStateField(Form $form, Country $country) {
        $form->add('state', 'entity', array(
            'class' => 'KMJToolkitBundle:State',
            'empty_value' => 'Please select a state',
            'required' => false,
            'query_builder' => function (EntityRepository $repository) use ($country) {
                $queryBuilder = $repository->createQueryBuilder('s');

                if ($country !== null) {
                    $queryBuilder->where('s.country = :country')
                            ->setParameter("country", $country);
                }

                return $queryBuilder;
            },
            'read_only' => false,
            'label' => 'State/Providence:',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function onPostSubmit(FormEvent $event) {
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $country = $event->getForm()->getData();
        $form = $event->getForm()->getParent();
        $this->buildStateField($form, $country);
    }

}
