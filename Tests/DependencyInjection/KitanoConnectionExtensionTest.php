<?php

namespace Kitano\ConnectionBundle\Tests\DependencyInjection;

use Kitano\ConnectionBundle\KitanoConnectionBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Kitano\ConnectionBundle\DependencyInjection\KitanoConnectionExtension;
use Symfony\Component\DependencyInjection\Definition;

class KitanoConnectionExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $container;
    
    /**
     * @var \Kitano\ConnectionBundle\DependencyInjection\KitanoConnectionExtension
     */
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new KitanoConnectionExtension();
        
        $this->container->setDefinition('doctrine.orm.entity_manager', new Definition('stdClass')); // w00t
        $this->container->setDefinition('validator', new Definition('stdClass')); // w00t
        $this->container->registerExtension($this->extension);
        
        $bundle = new KitanoConnectionBundle();
        $bundle->build($this->container); // Attach all default factories
    }

    public function tearDown()
    {
        unset($this->container, $this->extension);
    }
    
    public function testCustomConnectionManagedClass()
    {
        $config = array(
            "kitano_connection" => array (
                "persistence" => array (
                    "type" => "custom",
                    "managed_class" => array (
                        "connection" => "My\Entity\Connection",
                    ),
                ),
            ),
        );
        
        //User define a custom repository
        $this->container->setDefinition('kitano_connection.repository.connection', new Definition('My\CustomRepository'));
        
        
        $this->extension->load($config, $this->container);
        $this->container->compile();
        
        $this->assertEquals($this->container->getParameter('kitano_connection.managed_class.connection'), 'My\Entity\Connection');
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testCustomPersistenceType()
    {
        $config = array(
            "kitano_connection" => array (
                "persistence" => array (
                    "type" => "custom",
                ),
            ),
        );
        
        $this->extension->load($config, $this->container);
        $this->container->compile();
    }
    
    public function testOrmPeristenceDefaultManagedClass()
    {
        $config = array(
            "kitano_connection" => array (
                "persistence" => array (
                    "type" => "doctrine_orm",
                ),
            ),
        );
        
        $this->extension->load($config, $this->container);
        $this->container->compile();
        
        $this->assertEquals($this->container->getParameter('kitano_connection.managed_class.connection'), 'Kitano\ConnectionBundle\Entity\Connection');
    }
    
    public function testOrmPeristenceCustomManagedClass()
    {
        $config = array(
            "kitano_connection" => array (
                "persistence" => array (
                    "type" => "doctrine_orm",
                    "managed_class" => array (
                        "connection" => "My\Entity\Connection",
                    ),
                ),
            ),
        );
        
        $this->extension->load($config, $this->container);
        $this->container->compile();
        
        $this->assertEquals($this->container->getParameter('kitano_connection.managed_class.connection'), 'My\Entity\Connection');
    }
}
