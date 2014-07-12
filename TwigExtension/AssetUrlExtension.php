<?php

namespace KMJ\ToolkitBundle\TwigExtension;

use Symfony\Component\Routing\RouterInterface;
use Twig_Extension;
use Twig_Function_Method;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * @Service()
 * @Tag("twig.extension")
 */
class AssetUrlExtension extends Twig_Extension {

    protected $router;

    /**
     * @InjectParams({
     *     "router" = @Inject("router"),
     * })
     * 
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    /**
     * Declare the asset_url function
     */
    public function getFunctions() {
        return array(
            'asset_url' => new Twig_Function_Method($this, 'assetUrl'),
        );
    }

    /**
     * Implement asset_url function
     * We get the router context. This will default to settings in
     * parameters.yml if there is no active request
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
            $path = "/".$path;
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
