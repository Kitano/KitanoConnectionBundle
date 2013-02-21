<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;
use Kitano\ConnectionBundle\Repository\ArrayRepository;
use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\NodeInterface;

class ArrayRepositoryTest extends \PHPUnit_Framework_TestCase
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Model\Connection';

    /**
     * @var \Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();
        
        $this->repository = new ArrayRepository(static::CONNECTION_CLASS);
    }

    public function tearDown()
    {
        unset($this->repository);
        
        parent::tearDown();
    }

    public function testCreateEmptyConnectionReturnDoctrineOrmEntity()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }

    protected function createConnection(NodeInterface $nodeSource, NodeInterface $nodeDestination)
    {
        $connection = $this->repository->createEmptyConnection();

        $connection->setSource($nodeSource);
        $connection->setDestination($nodeDestination);
        $connection->setType(ConnectionInterface::STATUS_CONNECTED);
        
        return $connection;
    }
    
    public function testUpdate()
    {
        $connection = $this->createConnection(new Node(42), new Node(123));
        
        $this->assertEquals($connection, $this->repository->update($connection));
        $this->assertTrue($this->repository->getConnections()->contains($connection));
    }
    
    public function testDestroy()
    {
        $connection = $this->createConnection(new Node(42), new Node(123));
        
        $this->assertEquals($connection, $this->repository->update($connection));
        $this->assertEquals($this->repository, $this->repository->destroy($connection));
        $this->assertFalse($this->repository->getConnections()->contains($connection));
    }
    
    public function testGetConnectionsWithSource()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);
        
        $connection = $this->createConnection($nodeSource, $nodeDestination);
        
        $this->repository->update($connection);
        
        $this->assertContains($connection, $this->repository->getConnectionsWithSource($nodeSource));
    }
    
    public function testGetConnectionsWithSourceNotContains()
    {
        $nodeSource = new Node(42);
        
        $this->assertEquals(array(), $this->repository->getConnectionsWithSource($nodeSource));
    }
    
    public function testGetConnectionsWithDestination()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);
        
        $connection = $this->createConnection($nodeSource, $nodeDestination);
        
        $this->repository->update($connection);
        
        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination));
    }
    
    public function testGetConnectionsWithDestinationNotContains()
    {
        $nodeDestination = new Node(123);
        
        $this->assertEquals(array(), $this->repository->getConnectionsWithDestination($nodeDestination));
    }
}
