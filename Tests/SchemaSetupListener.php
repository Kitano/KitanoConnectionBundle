<?php
namespace Kitano\ConnectionBundle\Tests;

use DoctrineExtensions\PHPUnit\Event\EntityManagerEventArgs;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Class SchemaSetupListener
 *
 * @author Philippe Le Van <philippe.levan@kitpages.fr>
 * @see http://www.kitpages.fr/fr/cms/139/tests-unitaire-d_un-bundle-symfony2-avec-doctrine2
 */
class SchemaSetupListener
{
    public function preTestSetUp(EntityManagerEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();

        $schemaTool = new SchemaTool($em);

        $cmf = $em->getMetadataFactory();
        $classes = $cmf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
    }
}