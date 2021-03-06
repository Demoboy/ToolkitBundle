<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
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

/**
 * Address form for address entity.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @codeCoverageIgnore
 */
class AddressType extends AbstractType
{
    private $entityManager;

    /**
     * Basic constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['default_country'] === null && $options['include_country'] === false) {
            throw new InvalidArgumentException('Country was requested to be ignored, but no default country specified');
        }

        // initialize country to null if the order is unable to pull the address information
        // key relationship may be damaged from cloning the original database
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);

        $builder->add(
            'street',
            null,
            [
                'label' => 'kmjtoolkit.address.form.street.label',
                'required' => $options['required'],
            ]
        )
            ->add(
                'unit',
                null,
                [
                    'label' => 'kmjtoolkit.address.form.unit.label',
                    'required' => false,
                ]
            )
            ->add(
                'city',
                null,
                [
                    'label' => 'kmjtoolkit.address.form.city.label',
                    'required' => $options['required'],
                ]
            )
            ->add(
                'country',
                EntityType::class,
                [
                    'label' => 'kmjtoolkit.address.form.country.label',
                    'class' => 'KMJToolkitBundle:Country',
                    'required' => $options['required'],
                    'placeholder' => 'kmjtoolkit.address.form.country.empty_value',
                ]
            )
            ->add(
                'zipcode',
                null,
                [
                    'label' => 'kmjtoolkit.address.form.zipcode.label',
                    'required' => (!$options['include_country'] || $options['required']) ? false : true,
                ]
            );

        $builder->get('country')->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);

        if (!$options['include_country']) {
            $builder->remove('country');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Address::class,
                'required' => true,
                'default_country' => null,
                'include_country' => true,
            ]
        );

        $resolver->setAllowedTypes('include_country', 'boolean');
        $resolver->setAllowedTypes('required', 'boolean');
        $resolver->setAllowedTypes('default_country', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'kmj_toolkitbundle_address';
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
        }

        if ($country === null) {
            $defaultCountry = $form->getConfig()->getOption('default_country');

            if ($defaultCountry !== null) {
                $country = $this->entityManager->getRepository('KMJToolkitBundle:Country')
                    ->findOneByCode($defaultCountry);
            }
        }

        if ($country === null) {
            $form->add(
                'state',
                ChoiceType::class,
                [
                    'label' => 'kmjtoolkit.address.form.state.label',
                    'choices' => [],
                    'placeholder' => 'kmjtoolkit.address.form.state.empty_value',
                ]
            );
        } else {
            $this->buildStateField($form, $country);
        }
    }

    /**
     * Builds the state field.
     *
     * @param Form    $form    The form to add the field to
     * @param Country $country The country to add the states of
     */
    public function buildStateField(Form $form, Country $country)
    {
        $form->add(
            'state',
            EntityType::class,
            [
                'label' => 'kmjtoolkit.address.form.state.label',
                'placeholder' => 'kmjtoolkit.address.form.state.empty_value',
                'class' => 'KMJToolkitBundle:State',
                'required' => false,
                'query_builder' => function (EntityRepository $repository) use ($country) {
                    $queryBuilder = $repository->createQueryBuilder('s');

                    if ($country !== null) {
                        $queryBuilder->where('s.country = :country')
                            ->setParameter('country', $country);
                    }

                    return $queryBuilder;
                },
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function onPostSubmit(FormEvent $event)
    {
        $country = $event->getForm()->getData();
        $form = $event->getForm()->getParent();

        if ($country === null) {
            $defaultCountry = $form->getConfig()->getOption('default_country');

            if ($defaultCountry !== null) {
                $country = $this->entityManager->getRepository(Country::class)->findOneByCode($defaultCountry);
            }
        }

        $this->buildStateField($form, $country);
    }
}
