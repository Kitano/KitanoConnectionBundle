<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Manager\ConnectionManager;
use Kitano\ConnectionBundle\Proxy\Connection;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    private $connectionManager;

    public function setUp()
    {
        $connectionRepository = $this->getMock("Kitano\ConnectionBundle\ConnectionRepositoryInterface");
        $connectionRepository
                ->expects($this->any())
                 ->method('createEmptyConnection')
                 ->will($this->returnValue(new Connection()));
        
        $this->connectionManager = new ConnectionManager();
        $this->connectionManager->setConnectionRepository($connectionRepository);
    }

    public function tearDown()
    {
        unset($this->connectionManager);
    }
    
    public function testCreate()
    {
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        
        $connection = $this->connectionManager->create($objectA, $objectB, "follow");
        
        $this->assertInstanceOf('Kitano\ConnectionBundle\Model\Connection', $connection);
        $this->assertEquals($objectA, $connection->getSource());
        $this->assertEquals($objectB, $connection->getDestination());
        $this->assertEquals("follow", $connection->getType());
        $this->assertEquals(Connection::STATUS_CONNECTED, $connection->getStatus());
    }
    
    public function testGetConnectionsFrom()
    {
        $this->markTestIncomplete("Ce test n'a pas encore été implémenté.");
        
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        
        $connection = $this->connectionManager->create($objectA, $objectB, "follow");
        
        $connections = $this->connectionManager->getConnectionsFrom($objectA);
        
        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections->getIterator());
    }
    
    public function testGetConnectionsTo()
    {
        $this->markTestIncomplete("Ce test n'a pas encore été implémenté.");
        
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        
        $connection = $this->connectionManager->create($objectA, $objectB, "follow");
        
        $connections = $this->connectionManager->getConnectionsFrom($objectB);
        
        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections->getIterator());
    }
    
    public function testGetConnections()
    {
        $this->markTestIncomplete("Ce test n'a pas encore été implémenté.");
        
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\NodeInterface");
        
        $connection = $this->connectionManager->create($objectA, $objectB, "follow");
        
        $connectionsOnA = $this->connectionManager->getConnections($objectA);
        $connectionsOnB = $this->connectionManager->getConnections($objectB);
        
        $this->assertNotNull($connectionsOnA);
        $this->assertContains($connection, $connectionsOnA->getIterator());
        
        $this->assertNotNull($connectionsOnB);
        $this->assertContains($connection, $connectionsOnB->getIterator());
    }
}
