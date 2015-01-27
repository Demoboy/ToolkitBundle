<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */

namespace KMJ\ToolkitBundle\Tests\TwigExtension;

use KMJ\ToolkitBundle\TwigExtension\AssetUrlExtension;
use Twig_SimpleFilter;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \KMJ\ToolkitBundle\TwigExtension\AssertUrlExtension
 */
class AssetUrlExtensionTest extends PHPUnit_Framework_TestCase {

    protected $context;

    public function testGetFunctions() {
        $extension = $this->getExtension();
        $functions = $extension->getFunctions();
        $this->assertTrue($functions['asset_url'] instanceof Twig_SimpleFilter);
    }

    public function testAssetUrl() {
        $extension = $this->getExtension();

        $url = $extension->assetUrl("/rel/path/to/file.jpg");
        $this->assertTrue($url === "http://localhost:80/rel/path/to/file.jpg");

        $noSlashUrl = $extension->assetUrl("rel/path/to/file.jpg");
        $this->assertTrue($noSlashUrl === "http://localhost:80/rel/path/to/file.jpg");
        $this->context->setScheme("https");

        $httpsUrl = $extension->assetUrl("/rel/path/to/file.jpg");
        $this->assertTrue($httpsUrl === "https://localhost:443/rel/path/to/file.jpg");
    }

    public function testName() {
        $extension = $this->getExtension();
        $this->assertTrue($extension->getName() === "asset_url_extension");
    }

    protected function getExtension() {
        $this->context = $this->getMockBuilder("Symfony\Component\Routing\RequestContext")
                ->setMethods(null)
                ->getMock();

        $router = $this->getMockBuilder("Symfony\Bundle\FrameworkBundle\Routing\Router")
                ->disableOriginalConstructor()
                ->getMock();

        $router->method("getContext")
                ->will($this->returnValue($this->context));

        return new AssetUrlExtension($router);
    }

}
