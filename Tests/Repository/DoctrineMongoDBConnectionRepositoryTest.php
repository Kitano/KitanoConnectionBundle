<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Kitano\ConnectionBundle\Repository\DoctrineMongoDBConnectionRepository;
use Kitano\ConnectionBundle\Model\NodeInterface;
use Kitano\ConnectionBundle\Tests\MongoDBTestCase;
use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Document\Node;

class DoctrineMongoDBConnectionRepositoryTest extends MongoDBTestCase implements ConnectionRepositoryTestInterface
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

        $this->repository = new DoctrineMongoDBConnectionRepository($this->getDocumentManager(), static::CONNECTION_CLASS);
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

    /**
     * @group odm
     */
    public function testCreateEmptyConnectionReturn()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }

    /**
     * @group odm
     */
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

    /**
     * @group odm
     */
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

    /**
     * @group odm
     */
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

    /**
     * @group odm
     */
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

    /**
     * @group odm
     */
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

    /**
     * @group odm
     */
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

    /**
     * @group odm
     */
    public function testAreConnected()
    {
        $node1 = new Node(455);
        $node2 = new Node(4412);
        $node3 = new Node(4244);

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->persist($node3);
        $this->getDocumentManager()->flush();

        $connection1 = $this->createConnection($node1, $node2);
        $connection2 = $this->createConnection($node2, $node1);
        $connection3 = $this->createConnection($node1, $node3);

        $this->repository->update($connection1);
        $this->repository->update($connection2);
        $this->repository->update($connection3);

        $this->assertCount(1, $this->repository->areConnected($node1, $node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertCount(1, $this->repository->areConnected($node2, $node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertCount(1, $this->repository->areConnected($node1, $node3, array('type' => self::CONNECTION_TYPE)));
        $this->assertCount(0, $this->repository->areConnected($node2, $node3, array('type' => self::CONNECTION_TYPE)));

        $this->assertContains($connection1, $this->repository->areConnected($node1, $node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection2, $this->repository->areConnected($node2, $node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection3, $this->repository->areConnected($node1, $node3, array('type' => self::CONNECTION_TYPE)));

        $this->assertNotContains($connection3, $this->repository->areConnected($node3, $node1, array('type' => self::CONNECTION_TYPE)));
    }

    /**
     * @group odm
     */
    public function testGetConnections()
    {
        $node1 = new Node(455);
        $node2 = new Node(4412);
        $node3 = new Node(4244);

        $this->getDocumentManager()->persist($node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->persist($node3);
        $this->getDocumentManager()->flush();

        $connection1 = $this->createConnection($node1, $node2);
        $connection2 = $this->createConnection($node2, $node1);
        $connection3 = $this->createConnection($node1, $node3);

        $this->repository->update($connection1);
        $this->repository->update($connection2);
        $this->repository->update($connection3);

        $this->assertCount(3, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertCount(2, $this->repository->getConnections($node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertCount(1, $this->repository->getConnections($node3, array('type' => self::CONNECTION_TYPE)));

        $this->assertContains($connection1, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection2, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection3, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection3, $this->repository->getConnections($node3, array('type' => self::CONNECTION_TYPE)));

        $this->assertNotContains($connection3, $this->repository->getConnections($node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertNotContains($connection2, $this->repository->getConnections($node3, array('type' => self::CONNECTION_TYPE)));
    }
}
