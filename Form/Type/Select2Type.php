<?php
/*
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2016, SuperCru LLC
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use KMJ\ToolkitBundle\Form\DataTransformer\Select2DataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Select2Type.
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class Select2Type extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * Select2Type constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['entity_class'] !== null && $options['entity_property'] === null) {
            throw new \InvalidArgumentException('You must define an entity_property when entity_class is set');
        }

        parent::buildForm($builder, $options);

        $builder->resetViewTransformers();

        if ($options['entity_class'] !== null) {
            $builder->addModelTransformer(
                new Select2DataTransformer(
                    $this->manager,
                    $options['entity_class'],
                    $options['entity_property'],
                    $options['tags']
                )
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'tags' => false,
                'include_source' => false,
                'minimum_input' => 0,
                'route' => null,
                'process_results' => null,
                'handle_data' => null,
                'entity_class' => null,
                'entity_property' => null,
                'choices_as_values' => true,
                'theme' => 'classic',
            ]
        );

        $resolver->setRequired('process_results');
        $resolver->setRequired('route');
        $resolver->setRequired('handle_data');

        $resolver->setAllowedTypes('route', ['string']);
        $resolver->setAllowedTypes('process_results', ['string']);
        $resolver->setAllowedTypes('handle_data', ['string']);
        $resolver->setAllowedTypes('tags', ['boolean']);
        $resolver->setAllowedTypes('include_source', ['boolean']);
        $resolver->setAllowedTypes('minimum_input', ['integer']);
        $resolver->setAllowedTypes('entity_class', ['string', 'null']);
        $resolver->setAllowedTypes('entity_property', ['string', 'null']);
        $resolver->setAllowedTypes('theme', ['string']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_merge(
            $view->vars,
            [
                'tags' => $options['tags'],
                'include_source' => $options['include_source'],
                'minimum_input' => $options['minimum_input'],
                'route' => $options['route'],
                'handle_data' => $options['handle_data'],
                'process_results' => $options['process_results'],
                'theme' => $options['theme'],
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'select2';
    }
}
