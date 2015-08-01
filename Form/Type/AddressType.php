<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
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
class AddressType extends AbstractType
{

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
     * @param boolean $includeCountry Should the form include a country dropdown
     * @param boolean $required Should fields be marked as required
     */
    function __construct($includeCountry = true, $required = true)
    {
        $this->includeCountry = $includeCountry;
        $this->required = $required;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // initialize country to null if the order is unable to pull the address information
        // key relationship may be damaged from cloning the original database
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));

        $builder->add('street', null, array(
                /** @Desc("Street") */
                "label" => "kmjtoolkit.address.form.street.label",
                "required" => $this->required,
            ))
            ->add('unit', null, array(
                /** @Desc("Address (line 2)") */
                "label" => "kmjtoolkit.address.form.unit.label",
                "required" => false,
            ))
            ->add('city', null, array(
                /** @Desc("City") */
                "label" => "kmjtoolkit.address.form.city.label",
                "required" => $this->required,
            ))
            ->add('country', 'entity', array(
                /** @Desc("Country") */
                "label" => "kmjtoolkit.address.form.country.label",
                'class' => 'KMJToolkitBundle:Country',
                "required" => $this->required,
                /** @Desc("Please select a country") */
                "empty_value" => "kmjtoolkit.address.form.country.empty_value",
            ))
            ->add('zipcode', null, array(
                /** @Desc("Zipcode") */
                "label" => "kmjtoolkit.address.form.zipcode.label",
                "required" => (!$this->includeCountry || $this->required) ? false : true,
        ));

        $builder->get("country")->addEventListener(FormEvents::POST_SUBMIT, array($this, "onPostSubmit"));

        if (!$this->includeCountry) {
            $builder->remove("country");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KMJ\ToolkitBundle\Entity\Address'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "kmj_toolkit_address";
    }

    /**
     * {@inheritdoc}
     */
    public function onPreSetData(FormEvent $event)
    {
        $country = null;
        $data = $event->getData();
        $form = $event->getForm();

        if ($data instanceof Address) {
            $country = $data->getCountry();

            if ($country === null && $data->getState() !== null) {
                $country = $data->getState()->getCountry();
            }
        } else {
            $country = null;
        }

        if ($country === null) {
            $form->add("state", "choice", array(
                /** @Desc("State/Providence") */
                "label" => "kmjtoolkit.address.form.state.label",
                "choices" => array(),
                /** @Desc("Please select a state") */
                "empty_value" => "kmjtoolkit.address.form.state.empty_value",
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
    public function buildStateField(Form $form, Country $country)
    {
        $form->add('state', 'entity', array(
            /** @Desc("State/Providence") */
            "label" => "kmjtoolkit.address.form.state.label",
            /** @Desc("Please select a state") */
            "empty_value" => "kmjtoolkit.address.form.state.empty_value",
            'class' => 'KMJToolkitBundle:State',
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
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function onPostSubmit(FormEvent $event)
    {
        $country = $event->getForm()->getData();
        $form = $event->getForm()->getParent();
        $this->buildStateField($form, $country);
    }
}
