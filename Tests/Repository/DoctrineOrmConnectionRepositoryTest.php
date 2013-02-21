<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;
use Kitano\ConnectionBundle\Tests\OrmTestCase;
use Kitano\ConnectionBundle\Repository\DoctrineOrmConnectionRepository;
use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\NodeInterface;

class DoctrineOrmConnectionRepositoryTest extends OrmTestCase
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Entity\Connection';

    /**
     * @var \Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface
     */
    private $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        parent::setUp();
        
        $this->repository = new DoctrineOrmConnectionRepository($this->getEntityManager(), static::CONNECTION_CLASS);
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

    public function testExtractedClassMetadata()
    {
        $node = new Node();
        $node->setId(123);
        $expectedMetadata = array(
            'object_class' => 'Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node',
            'object_id' => 123,
        );

        $method = new \ReflectionMethod($this->repository, 'extractMetadata');
        $method->setAccessible(true);
        $metadata = $method->invoke($this->repository, $node);

        $this->assertEquals($metadata, $expectedMetadata);
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
        $this->assertEquals($connection, $this->getEntityManager()->find(self::CONNECTION_CLASS, $connection->getId()));
    }
    
    public function testDestroy()
    {
        $connection = $this->createConnection(new Node(42), new Node(123));
        
        $this->assertEquals($connection, $this->repository->update($connection));
        
        $id = $connection->getId();
        
        $this->assertEquals($this->repository, $this->repository->destroy($connection));
        $this->assertNull($this->getEntityManager()->find(self::CONNECTION_CLASS, $id));
    }
    
    public function testGetConnectionsWithSource()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);
        
        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();
        
        $connection = $this->createConnection($nodeSource, $nodeDestination);
        
        $this->repository->update($connection);
        
        $this->assertContains($connection, $this->repository->getConnectionsWithSource($nodeSource));
    }
    
    public function testGetConnectionsWithSourceNotContains()
    {
        $this->markTestIncomplete("Ce test n'a pas encore été validé.");
        
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);
        
        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();
        
        $this->assertInstanceOf('array', $this->repository->getConnectionsWithSource($nodeSource));
        $this->assertEquals(array(), $this->repository->getConnectionsWithSource($nodeSource));
    }
    
    public function testGetConnectionsWithDestination()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);
        
        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();
        
        $connection = $this->createConnection($nodeSource, $nodeDestination);
        
        $this->repository->update($connection);
        
        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination));
    }
    
    public function testGetConnectionsWithDestinationNotContains()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);
        
        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();
        
        var_dump($this->repository->getConnectionsWithDestination($nodeDestination));
        
        $this->assertEquals(array(), $this->repository->getConnectionsWithDestination($nodeDestination));
    }
}
