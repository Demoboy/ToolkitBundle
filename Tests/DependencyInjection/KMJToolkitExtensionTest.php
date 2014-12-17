<?php

namespace KMJ\ToolkitBundle\Tests\DependencyInjection;

use KMJ\ToolkitBundle\DependencyInjection\KMJToolkitExtension;
use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-30 at 12:23:35.
 */
class KMJToolkitExtensionTest extends PHPUnit_Framework_TestCase {

    public function testLoad() {
        $containerBuilder = $this->getMockBuilder("Symfony\Component\DependencyInjection\ContainerBuilder")
                ->getMock();

        $extension = new KMJToolkitExtension(array(), $containerBuilder);

    }

}
