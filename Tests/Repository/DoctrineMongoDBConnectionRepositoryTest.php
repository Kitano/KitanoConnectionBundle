<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Kitano\ConnectionBundle\Repository\DoctrineMongoDBConnectionRepository;
use Kitano\ConnectionBundle\Model\NodeInterface;
use Kitano\ConnectionBundle\Tests\MongoDBTestCase;

class DoctrineMongoDBConnectionRepositoryTest extends MongoDBTestCase
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Document\Connection';
    const CONNECTION_TYPE = 'like';

    /**
     * @var \Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new DoctrineMongoDBConnectionRepository($this->createDocumentManager(), static::CONNECTION_CLASS);
    }

    public function testCreateEmptyConnectionReturnDoctrineMongoDBDocument()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }
}