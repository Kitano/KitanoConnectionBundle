<?php

namespace Kitano\ConnectionBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache;

use DoctrineExtensions\PHPUnit\OrmTestCase as BaseOrmTestCase;

/**
 * Class OrmTestCase
 *
 * @author Philippe Le Van <philippe.levan@kitpages.fr>
 * @see http://www.kitpages.fr/fr/cms/139/tests-unitaire-d_un-bundle-symfony2-avec-doctrine2
 */
class OrmTestCase extends BaseOrmTestCase
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function createEntityManager()
    {
        // event manager used to create schema before tests
        $eventManager = new EventManager();
        $eventManager->addEventListener(array("preTestSetUp"), new SchemaSetupListener());

        // doctrine xml configs and namespaces
        $prefixList = array();
        if (is_dir(__DIR__.'/../Resources/config/doctrine')) {
            $dir = __DIR__.'/../Resources/config/doctrine';
            $prefixList[$dir] = 'Kitano\ConnectionBundle\Entity';
        }
        if (is_dir(__DIR__.'/Fixtures/Doctrine/Mapping')) {
            $dir = __DIR__.'/Fixtures/Doctrine/Mapping';
            $prefixList[$dir] = 'Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity';
        }

        // create drivers (that reads xml configs)
        $driver = new \Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver($prefixList);

        // create config object
        $config = new Configuration();
        $config->setMetadataCacheImpl(new ArrayCache());
        $config->setMetadataDriverImpl($driver);

        $config->setProxyDir(__DIR__.'/DoctrineProxies');
        $config->setProxyNamespace('Kitano\ConnectionBundle\Tests\DoctrineProxies');
        $config->setAutoGenerateProxyClasses(true);

        //$config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());

        // create entity manager
        $em = EntityManager::create(
            array(
                'driver' => 'pdo_sqlite',
                'path' => "/tmp/sqlite-test.db"
            ),
            $config,
            $eventManager
        );

        return $em;
    }

    protected function getDataSet()
    {
//        return $this->createFlatXmlDataSet(__DIR__."/_doctrine/dataset/entityFixture.xml");
    }
}