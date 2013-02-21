<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase {
    public function testDefaultValue()
    {
        $connection = new Connection();
        
        $this->assertNotNull($connection->getCreatedAt());
        $this->assertNull($connection->getConnectedAt());
        $this->assertNull($connection->getDisconnectedAt());
        $this->assertEquals(ConnectionInterface::STATUS_DISCONNECTED, $connection->getType());
    }
}
