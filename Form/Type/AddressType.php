<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */
namespace KMJ\ToolkitBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use KMJ\ToolkitBundle\Entity\Address;
use KMJ\ToolkitBundle\Entity\Country;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Address form for address entity
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @codeCoverageIgnore
 */
class AddressType extends AbstractType
{

    private $em;

    /**
     * Basic constructor
     * 
     */
    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options["default_country"] === null && $options["include_country"] === false) {
            throw new InvalidArgumentException("Country was requested to be ignored, but no default country specified");
        }
        
        // initialize country to null if the order is unable to pull the address information
        // key relationship may be damaged from cloning the original database
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));

        $builder->add('street', null, array(
                /** @Desc("Street") */
                "label" => "kmjtoolkit.address.form.street.label",
                "required" => $options['required'],
            ))
            ->add('unit', null, array(
                /** @Desc("Address (line 2)") */
                "label" => "kmjtoolkit.address.form.unit.label",
                "required" => false,
            ))
            ->add('city', null, array(
                /** @Desc("City") */
                "label" => "kmjtoolkit.address.form.city.label",
                "required" => $options['required'],
            ))
            ->add('country', EntityType::class, array(
                /** @Desc("Country") */
                "label" => "kmjtoolkit.address.form.country.label",
                'class' => 'KMJToolkitBundle:Country',
                "required" => $options['required'],
                /** @Desc("Please select a country") */
                "placeholder" => "kmjtoolkit.address.form.country.empty_value",
            ))
            ->add('zipcode', null, array(
                /** @Desc("Zipcode") */
                "label" => "kmjtoolkit.address.form.zipcode.label",
                "required" => (!$options['include_country'] || $options['required']) ? false : true,
        ));

        $builder->get("country")->addEventListener(FormEvents::POST_SUBMIT, array($this, "onPostSubmit"));

        if (!$options['include_country']) {
            $builder->remove("country");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'KMJ\ToolkitBundle\Entity\Address',
            "required" => true,
            "default_country" => null,
            "include_country" => true,
        ));

        $resolver->setAllowedTypes("include_country", "boolean");
        $resolver->setAllowedTypes("required", "boolean");
        $resolver->setAllowedTypes("default_country", ["null", "string"]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "address";
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
            $defaultCountry = $form->getConfig()->getOption("defaultCountry");

            if ($defaultCountry !== null) {
                $country = $this->em->getRepository("KMJToolkitBundle:Country")->findOneByCode($defaultCountry);
            }
        }

        if ($country === null) { 
            $form->add("state", ChoiceType::class, array(
                /** @Desc("State/Providence") */
                "label" => "kmjtoolkit.address.form.state.label",
                "choices" => array(),
                /** @Desc("Please select a state") */
                "placeholder" => "kmjtoolkit.address.form.state.empty_value",
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
        $form->add('state', EntityType::class, array(
            /** @Desc("State/Providence") */
            "label" => "kmjtoolkit.address.form.state.label",
            /** @Desc("Please select a state") */
            "placeholder" => "kmjtoolkit.address.form.state.empty_value",
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
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function onPostSubmit(FormEvent $event)
    {
        $country = $event->getForm()->getData();
        $form = $event->getForm()->getParent();

        if ($country === null) {
            $defaultCountry = $form->getConfig()->getOption("defaultCountry");

            if ($defaultCountry !== null) {
                $country = $this->em->getRepository("KMJToolkitBundle:Country")->findOneByCode($defaultCountry);
            }
        }

        $this->buildStateField($form, $country);
    }
}
