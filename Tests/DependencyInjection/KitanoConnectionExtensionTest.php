<?php

namespace Kitano\ConnectionBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Kitano\ConnectionBundle\DependencyInjection\KitanoConnectionExtension;

class KitanoConnectionExtensionTest extends \PHPUnit_Framework_TestCase {
    private $container;
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new KitanoConnectionExtension();
    }

    public function tearDown()
    {
        unset($this->container, $this->extension);
    }
    
    public function testContextDefinition()
    {
        $this->markTestIncomplete("Ce test n'a pas encore été implémenté.");
        
        $config = array(
            "kitano_connection" => array (
            ),
        );
        
        $this->extension->load($config, $this->container);
    }
}
