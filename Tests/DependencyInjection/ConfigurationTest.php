<?php

namespace Kitano\ConnectionBundle\Tests\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;

use Kitano\ConnectionBundle\DependencyInjection\Configuration;

class ConfigurationText extends \PHPUnit_Framework_TestCase {
    private $configuration;
    private $processor;

    public function setUp()
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function tearDown()
    {
        unset($this->configuration, $this->processor);
    }
    
    public function testStandardDefinition()
    {
        $configRaw = array(
            "kitano_connection" => array (
            ),
        );
        
        $config = $this->processor->processConfiguration($this->configuration, $configRaw);
    }
    
    public function testNoDefinition()
    {
        $configRaw = array(
            "kitano_connection" => array (),
        );
        
        $config = $this->processor->processConfiguration($this->configuration, $configRaw);
    }
}
