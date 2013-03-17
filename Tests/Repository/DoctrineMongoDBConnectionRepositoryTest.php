<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Kitano\ConnectionBundle\Repository\DoctrineMongoDBConnectionRepository;
use Kitano\ConnectionBundle\Model\NodeInterface;
use Kitano\ConnectionBundle\Tests\MongoDBTestCase;
use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Document\Node;

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

        $this->repository = new DoctrineMongoDBConnectionRepository($this->documentManager, static::CONNECTION_CLASS);
    }

    protected function createConnection(NodeInterface $nodeSource, NodeInterface $nodeDestination)
    {
        $connection = $this->repository->createEmptyConnection();

        $connection->setSource($nodeSource);
        $connection->setDestination($nodeDestination);
        $connection->setType(self::CONNECTION_TYPE);

        return $connection;
    }

    public function testCreateEmptyConnectionReturnDoctrineMongoDBDocument()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }

    public function testUpdate()
    {
        $node1 = new Node(new \MongoId());
        $node2 = new Node(new \MongoId());

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->createConnection($node1, $node2);

        $this->assertEquals($connection, $this->repository->update($connection));
        $this->assertEquals($connection, $this->getDocumentManager()->find(self::CONNECTION_CLASS, $connection->getId()));
        $this->assertEquals($connection->getSource(), $node1);
        $this->assertEquals($connection->getDestination(), $node2),
    }
}