<?php
/**
 * This file is part of the KMJToolkitBundle.
 *
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
class KMJToolkitExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('kmj.toolkit.service.parameters', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (true === class_exists("Nelmio\Alice\ProcessorInterface")) {
            $loader->load('alice.yml');
        }

        if (true === class_exists("Knp\Menu\MenuItem")) {
            $loader->load('knp_menu.yml');
        }

        if ($config['aws']['enabled'] === true) {
            foreach ($this->convertToStringParams($config['aws'], 'kmj.toolkit.aws') as $key => $value) {
                $container->setParameter($key, $value);
            }

            $container->setParameter('kmj.toolkit.aws', $config['aws']);
            $loader->load('aws.yml');
        }
    }

    private function convertToStringParams(array $configs, string $prefix): array
    {
        $normalizedConfigs = [];

        if ($prefix !== '') {
            $prefix = $prefix.'.';
        }

        foreach ($configs as $key => $value) {
            if (is_array($value)) {
                foreach ($this->convertToStringParams($value, '') as $nestedKey => $nestedValue) {
                    $normalizedConfigs[$prefix.strtolower($key).'.'.$nestedKey] = $nestedValue;
                }
            } else {
                $normalizedConfigs[$prefix.strtolower($key)] = $value;
            }
        }

        return $normalizedConfigs;
    }
}
