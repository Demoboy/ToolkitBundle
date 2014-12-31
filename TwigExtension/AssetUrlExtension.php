<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\TwigExtension;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Symfony\Component\Routing\RouterInterface;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Creates absolute urls in twig
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 * @Service()
 * @Tag("twig.extension")
 */
class AssetUrlExtension extends Twig_Extension {

    /**
     * The router component
     * @var RouterInterface
     */
    protected $router;

    /**
     * Basic constructor
     * @InjectParams({
     *     "router" = @Inject("router"),
     * })
     *
     * @param RouterInterface $router The router component
     */
    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    /**
     * Declare the asset_url function
     */
    public function getFunctions() {
        return array(
            'asset_url' => new Twig_SimpleFilter($this, 'assetUrl'),
        );
    }

    /**
     * Implement asset_url function
     * We get the router context. This will default to settings in
     * parameters.yml if there is no active request
     * @param string $path The relative web path
     * @return type
     */
    public function assetUrl($path) {
        $context = $this->router->getContext();

        if ($context->getScheme() == "http") {
            $port = $context->getHttpPort();
        } else {
            $port = $context->getHttpsPort();
        }

        $host = "{$context->getScheme()}://{$context->getHost()}:{$port}{$context->getBaseUrl()}";

        if (substr($path, 0, 1) != "/") {
            $path = "/" . $path;
        }

        return $host . $path;
    }

    /**
     * Set a name for the extension
     */
    public function getName() {
        return 'asset_url_extension';
    }

}
