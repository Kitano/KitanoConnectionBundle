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
}
