<?php
/*
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2016, SuperCru LLC
 */

namespace KMJ\ToolkitBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Select2Type
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class Select2Type extends ChoiceType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            "tags" => false,
            "include_source" => false,
            "minimum_input" => 0,
            "route" => null,
            "process_results" => null,
            "handle_data" => null,
        ]);
        
        $resolver->setRequired("process_results");
        $resolver->setRequired("route");
        $resolver->setRequired("handle_data");

        $resolver->setAllowedTypes("route", ["string"]);
        $resolver->setAllowedTypes("process_results", ["string"]);
        $resolver->setAllowedTypes("handle_data", ["string"]);
        $resolver->setAllowedTypes("tags", ["boolean"]);
        $resolver->setAllowedTypes("include_source", ["boolean"]);
        $resolver->setAllowedTypes("minimum_input", ["integer"]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge($view->vars, [
            "tags" => $options['tags'],
            "include_source" => $options['include_source'],
            "minimum_input" => $options['minimum_input'],
            "route" => $options['route'],
            "handle_data" => $options['handle_data'],
            "process_results" => $options['process_results'],
        ]);
        
        return parent::buildView($view, $form, $options);
    }

    public function getBlockPrefix()
    {
        return "select2";
    }
}
