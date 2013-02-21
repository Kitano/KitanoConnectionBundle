<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;
use Kitano\ConnectionBundle\Manager\ConnectionManager;
use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Repository\ArrayRepository;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase {
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Model\Connection';
    
    /**
     * @var \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    private $connectionManager;

    public function setUp()
    {
        $this->connectionManager = new ConnectionManager();
        $this->connectionManager->setFilterValidator($this->getFilterValidatorMock());
        $this->connectionManager->setConnectionRepository(new ArrayRepository(self::CONNECTION_CLASS));
    }
    
    protected function getFilterValidatorMock()
    {
        $mock = $this->getMockBuilder('Kitano\ConnectionBundle\Manager\FilterValidator')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    public function tearDown()
    {
        unset($this->connectionManager);
    }
    
    public function testCreate()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();
        
        $connection = $this->connectionManager->create($nodeSource, $nodeDestination, "follow");
        
        $this->assertInstanceOf('Kitano\ConnectionBundle\Model\Connection', $connection);
        $this->assertEquals($nodeSource, $connection->getSource());
        $this->assertEquals($nodeDestination, $connection->getDestination());
        $this->assertEquals("follow", $connection->getType());
        $this->assertEquals(ConnectionInterface::STATUS_CONNECTED, $connection->getStatus());
    }
    
    public function testGetConnectionsFrom()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();
        
        $connection = $this->connectionManager->create($nodeSource, $nodeDestination, "follow");
        
        $connections = $this->connectionManager->getConnectionsFrom($nodeSource);
        
        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections);
    }
    
    public function testGetConnectionsTo()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();
        
        $connection = $this->connectionManager->create($nodeSource, $nodeDestination, "follow");
        
        $connections = $this->connectionManager->getConnectionsTo($nodeDestination);

        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections);
    }
    
    public function testGetConnections()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();
        
        $connection = $this->connectionManager->create($nodeSource, $nodeDestination, "follow");
        
        $connectionsOnA = $this->connectionManager->getConnections($nodeSource);
        $connectionsOnB = $this->connectionManager->getConnections($nodeDestination);
        
        $this->assertNotNull($connectionsOnA);
        $this->assertContains($connection, $connectionsOnA->getIterator());
        
        $this->assertNotNull($connectionsOnB);
        $this->assertContains($connection, $connectionsOnB->getIterator());
    }
}
