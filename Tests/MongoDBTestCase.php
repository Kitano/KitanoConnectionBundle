<?php

namespace Kitano\ConnectionBundle\Tests;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver;
use Doctrine\ODM\MongoDB\SchemaManager;
use Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator;
use Doctrine\Common\EventManager;

class MongoDBTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    protected function setUp()
    {
        if (!class_exists('Mongo')) {
            $this->markTestSkipped('Mongo PHP/PECL Extension is not available.');
        }
        if (!class_exists('Doctrine\\ODM\\MongoDB\\Version')) {
            $this->markTestSkipped('Doctrine MongoDB ODM is not available.');
        }
        try {
            new \Mongo();
        } catch (\MongoException $e) {
            $this->markTestSkipped('Unable to connect to Mongo.');
        }

        $this->documentManager = $this->createDocumentManager();
    }

    protected function tearDown()
    {
        if($this->documentManager instanceof DocumentManager)
        {
            $cmf = $this->documentManager->getMetadataFactory();
            $schemaManager = new SchemaManager($this->documentManager, $cmf);
            $schemaManager->dropDatabases();
        }
    }

    protected function createDocumentManager()
    {
        // doctrine xml configs and namespaces
        $prefixList = array();
        if (is_dir(__DIR__.'/../Resources/config/doctrine')) {
            $dir = __DIR__.'/../Resources/config/doctrine';
            $prefixList[$dir] = 'Kitano\ConnectionBundle\Document';
        }
        if (is_dir(__DIR__.'/Fixtures/Doctrine/Mapping')) {
            $dir = __DIR__.'/Fixtures/Doctrine/Mapping';
            $prefixList[$dir] = 'Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Document';
        }

        $driver = new XmlDriver(new SymfonyFileLocator($prefixList, '.mongodb.xml'), '.mongodb.xml');

        $config = new Configuration();
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(\sys_get_temp_dir());
        $config->setHydratorDir(\sys_get_temp_dir());
        $config->setProxyNamespace('Kitano\ConnectionBundle\Tests\DoctrineMongoDBProxies');
        $config->setHydratorNamespace('Kitano\ConnectionBundle\Tests\DoctrineMongoDBHydrators');
        $config->setMetadataDriverImpl($driver);
        $config->setMetadataCacheImpl(new ArrayCache());

        return DocumentManager::create(new Connection(new \Mongo()), $config, new EventManager());
    }

    public function getDocumentManager()
    {
        return $this->documentManager;
    }
}