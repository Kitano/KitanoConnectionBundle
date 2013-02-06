<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Manager\ConnectionManager;
use Kitano\ConnectionBundle\Model\Connection;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase {
    private $connectionManager;

    public function setUp()
    {
        $this->connectionManager = new ConnectionManager();
    }

    public function tearDown()
    {
        unset($this->connectionManager);
    }
    
    public function testConnect()
    {
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectManager = $this->getMock("Doctrine\Common\Persistence\ObjectManager");
        $objectManager->expects($this->once())->method('persist');
        $objectManager->expects($this->once())->method('flush');
        
        $this->connectionManager->setobjectManager($objectManager);
        $connection = $this->connectionManager->connect($objectA, $objectB);
        
        $this->assertInstanceOf('Kitano\ConnectionBundle\Model\Connection', $connection);
        $this->assertEquals($objectA, $connection->getSource());
        $this->assertEquals($objectB, $connection->getDestination());
        $this->assertEquals(Connection::STATUS_CONNECTED, $connection->getStatus());
    }
    
    public function testAreConnected()
    {
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectManager = $this->getMock("Doctrine\Common\Persistence\ObjectManager");
        
        $this->connectionManager->setobjectManager($objectManager);
        $this->connectionManager->connect($objectA, $objectB);
        
        $this->assertTrue($this->connectionManager->areConnected($objectA, $objectB));
    }
    
    public function testGetConnectionTo()
    {
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectManager = $this->getMock("Doctrine\Common\Persistence\ObjectManager");
        
        $this->connectionManager->setobjectManager($objectManager);
        $connection = $this->connectionManager->connect($objectA, $objectB);
        
        $this->assertContains($connection, $this->connectionManager->getConnectionTo($objectB));
    }
    
    public function testGetConnectionFrom()
    {
        $objectA = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectB = $this->getMock("Kitano\ConnectionBundle\Model\Connectable");
        $objectManager = $this->getMock("Doctrine\Common\Persistence\ObjectManager");
        
        $this->connectionManager->setobjectManager($objectManager);
        $connection = $this->connectionManager->connect($objectA, $objectB);
        
        $this->assertContains($connection, $this->connectionManager->getConnectionFrom($objectA));
    }
}
