<?php

namespace Kitano\ConnectionBundle\Tests\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Kitano\ConnectionBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidPersistenceType()
    {
        $processor = new Processor();
        $configuration = new Configuration(array());

        $processor->processConfiguration($configuration, array(
            array(
                'persistence' => array(
                    'type' => 'my_awesome_orm',
                ),
            )
        ));
    }

    public function testDoctrineOrmPersistenceType()
    {
        $processor = new Processor();
        $configuration = new Configuration(array());

        $config = $processor->processConfiguration($configuration, array(
            array(
                'persistence' => array(
                    'type' => 'doctrine_orm',
                ),
            )
        ));

        $this->assertEquals(array('type' => 'doctrine_orm'), $config['persistence']);
    }

    public function testDoctrineMongoDbPersistenceType()
    {
        $processor = new Processor();
        $configuration = new Configuration(array());

        $config = $processor->processConfiguration($configuration, array(
            array(
                'persistence' => array(
                    'type' => 'doctrine_mongodb',
                ),
            )
        ));

        $this->assertEquals(array('type' => 'doctrine_mongodb'), $config['persistence']);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testEmptyConnectionManagedClass()
    {
        $processor = new Processor();
        $configuration = new Configuration(array());

        $processor->processConfiguration($configuration, array(
            array(
                'persistence' => array(
                    'managed_class' => array('connection' => null),
                ),
            )
        ));
    }

    protected static function getBundleDefaultConfig()
    {
        return array();
    }
}
