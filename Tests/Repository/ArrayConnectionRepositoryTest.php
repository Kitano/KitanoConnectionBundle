<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Doctrine\Common\Collections\ArrayCollection;

use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;
use Kitano\ConnectionBundle\Repository\ArrayConnectionRepository;
use Kitano\ConnectionBundle\Model\NodeInterface;

class ArrayConnectionRepositoryTest extends \PHPUnit_Framework_TestCase implements ConnectionRepositoryTestInterface
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Model\Connection';
    const CONNECTION_TYPE = 'follow';

    /**
     * @var \Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new ArrayConnectionRepository(static::CONNECTION_CLASS);
    }

    public function tearDown()
    {
        unset($this->repository);

        parent::tearDown();
    }

    protected function getFilters()
    {
        return array (
            'type' => self::CONNECTION_TYPE,
        );
    }

    /**
     * @group array
     */
    public function testCreateEmptyConnectionReturn()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }

    protected function createConnection(NodeInterface $nodeSource, NodeInterface $nodeDestination)
    {
        $connection = $this->repository->createEmptyConnection();

        $connection->setSource($nodeSource);
        $connection->setDestination($nodeDestination);
        $connection->setType(self::CONNECTION_TYPE);

        return $connection;
    }

    /**
     * @group array
     */
    public function testUpdate()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->assertEquals($connection, $this->repository->update($connection));

        $this->assertContains($connection, $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }

    /**
     * @group array
     */
    public function testDestroy()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->assertEquals($connection, $this->repository->update($connection));
        $this->assertEquals($this->repository, $this->repository->destroy(new ArrayCollection(array($connection))));

        $this->assertNotContains($connection, $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
        $this->assertNotContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }

    /**
     * @group array
     */
    public function testGetConnectionsWithSource()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
    }

    /**
     * @group array
     */
    public function testGetConnectionsWithSourceNotContains()
    {
        $nodeSource = new Node(42);

        $this->assertEquals(array(), $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
    }

    /**
     * @group array
     */
    public function testGetConnectionsWithDestination()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }

    /**
     * @group array
     */
    public function testGetConnectionsWithDestinationNotContains()
    {
        $nodeDestination = new Node(123);

        $this->assertEquals(array(), $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }

    /**
     * @group array
     */
    public function testAreConnected()
    {
        $node1 = new Node(455);
        $node2 = new Node(4412);
        $node3 = new Node(4244);

        $connection1 = $this->createConnection($node1, $node2);
        $connection2 = $this->createConnection($node2, $node1);
        $connection3 = $this->createConnection($node1, $node3);

        $this->repository->update($connection1);
        $this->repository->update($connection2);
        $this->repository->update($connection3);

        $this->assertTrue($this->repository->areConnected($node1, $node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertTrue($this->repository->areConnected($node2, $node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertTrue($this->repository->areConnected($node1, $node3, array('type' => self::CONNECTION_TYPE)));
        $this->assertFalse($this->repository->areConnected($node2, $node3, array('type' => self::CONNECTION_TYPE)));
    }

    /**
     * @group array
     */
    public function testGetConnections()
    {
        $node1 = new Node(455);
        $node2 = new Node(4412);
        $node3 = new Node(4244);

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
