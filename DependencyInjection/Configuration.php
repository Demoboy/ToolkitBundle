<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kmj_toolkit');

        $rootNode->children()
                    ->scalarNode("rootdir")
                        ->defaultValue("%kernel.root_dir%")
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode("enckey")
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->arrayNode('administrator')
                        ->children()
                            ->scalarNode('firstname')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('lastname')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                                ->scalarNode('username')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('email')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                                ->scalarNode('password')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }

}
