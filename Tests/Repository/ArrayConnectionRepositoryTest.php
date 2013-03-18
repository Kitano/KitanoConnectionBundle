<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

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

    public function testUpdate()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->assertEquals($connection, $this->repository->update($connection));

        $this->assertContains($connection, $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }

    public function testDestroy()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->assertEquals($connection, $this->repository->update($connection));
        $this->assertEquals($this->repository, $this->repository->destroy($connection));

        $this->assertNotContains($connection, $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
        $this->assertNotContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }

    public function testGetConnectionsWithSource()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
    }

    public function testGetConnectionsWithSourceNotContains()
    {
        $nodeSource = new Node(42);

        $this->assertEquals(array(), $this->repository->getConnectionsWithSource($nodeSource, $this->getFilters()));
    }

    public function testGetConnectionsWithDestination()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }

    public function testGetConnectionsWithDestinationNotContains()
    {
        $nodeDestination = new Node(123);

        $this->assertEquals(array(), $this->repository->getConnectionsWithDestination($nodeDestination, $this->getFilters()));
    }
}
