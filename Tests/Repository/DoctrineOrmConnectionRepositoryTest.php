<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Kitano\ConnectionBundle\Tests\OrmTestCase;
use Kitano\ConnectionBundle\Repository\DoctrineOrmConnectionRepository;

class DoctrineOrmConnectionRepositoryTest extends OrmTestCase
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Entity\Connection';

    /**
     * @var \Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface
     */
    private $repository;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $this->em = $this->getEntityManager();
        $this->repository = new DoctrineOrmConnectionRepository($this->em, static::CONNECTION_CLASS);
    }

    public function tearDown()
    {
        unset($this->em);
        unset($this->repository);
    }

    public function testCreateEmptyConnectionReturnDoctrineOrmEntity()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }
}
