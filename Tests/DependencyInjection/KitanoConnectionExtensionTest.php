<?php

namespace Kitano\ConnectionBundle\Tests\DependencyInjection;

use Kitano\ConnectionBundle\KitanoConnectionBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Kitano\ConnectionBundle\DependencyInjection\KitanoConnectionExtension;
use Symfony\Component\DependencyInjection\Definition;

abstract class KitanoConnectionExtensionTest extends \PHPUnit_Framework_TestCase
{
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
    
    abstract protected function loadFromFile(ContainerBuilder $container, $file);

    public function testCustomConnectionManagedClass()
    {
        $container = $this->getContainer('persistence_custom', true);

        $this->assertEquals($container->getParameter('kitano_connection.managed_class.connection'),
            'My\Entity\Connection');
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testCustomPersistenceType()
    {
        $container = $this->getContainer('persistence_custom', false);
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
    
    protected function getContainer($file, $createCustomRepository = true)
    {
        $container = new ContainerBuilder();

        if ($createCustomRepository) {
            $definition = new Definition('My\CustomRepository');
            $container->setDefinition('kitano_connection.repository.connection', $definition);
        }

        $kitanoConnection = new KitanoConnectionExtension();
        $container->registerExtension($kitanoConnection);

        $bundle = new KitanoConnectionBundle();
        $bundle->build($container); // Attach all default factories
        $this->loadFromFile($container, $file);

        $container->compile();

        return $container;
    }
}
