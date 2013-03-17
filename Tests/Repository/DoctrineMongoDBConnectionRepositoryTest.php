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

    protected function createNode()
    {
        return new Node(new \MongoId());
    }

    public function testCreateEmptyConnectionReturnDoctrineMongoDBDocument()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }

    public function testUpdate()
    {
        $node1 = $this->createNode();
        $node2 = $this->createNode();

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->createConnection($node1, $node2);

        $this->assertEquals($connection, $this->repository->update($connection));
        $this->assertEquals($connection, $this->getDocumentManager()->find(self::CONNECTION_CLASS, $connection->getId()));
        $this->assertEquals($connection->getSource(), $node1);
        $this->assertEquals($connection->getDestination(), $node2);
    }

    public function testDestroy()
    {
        $node1 = $this->createNode();
        $node2 = $this->createNode();

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->createConnection($node1, $node2);

        $this->assertEquals($connection, $this->repository->update($connection));

        $id = $connection->getId();

        $this->assertEquals($this->repository, $this->repository->destroy($connection));
        $this->assertNull($this->getDocumentManager()->find(self::CONNECTION_CLASS, $id));
    }

    public function testGetConnectionsWithSource()
    {
        $node1 = $this->createNode();
        $node2 = $this->createNode();

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->createConnection($node1, $node2);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithSource($node1));
        $this->assertContains($connection, $this->repository->getConnectionsWithSource($node1, array('type' => self::CONNECTION_TYPE)));
    }

    public function testGetConnectionsWithSourceNotContains()
    {
        $node1 = $this->createNode();
        $node2 = $this->createNode();

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->createConnection($node1, $node2);
        $this->assertEquals($connection, $this->repository->update($connection));

        $this->assertEmpty($this->repository->getConnectionsWithSource($node2));
    }

    public function testGetConnectionsWithDestination()
    {
        $node1 = $this->createNode();
        $node2 = $this->createNode();

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->createConnection($node1, $node2);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($node2));
        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($node2, array('type' => self::CONNECTION_TYPE)));
    }

    public function testGetConnectionsWithDestinationNotContains()
    {
        $node1 = $this->createNode();
        $node2 = $this->createNode();

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->createConnection($node1, $node2);
        $this->assertEquals($connection, $this->repository->update($connection));

        $this->assertEmpty($this->repository->getConnectionsWithDestination($node1));
    }
}