<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Model\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase {
    public function testDefaultValue()
    {
        $connection = new Connection();
        
        $this->assertNotNull($connection->getCreatedAt());
        $this->assertNull($connection->getType());
        $this->assertNull($connection->getSource());
        $this->assertNull($connection->getDestination());
    }
    
    public function testRemoveStatus()
    {
        $connection = new Connection();
        
        $this->assertFalse(method_exists($connection, 'getStatus'));
        $this->assertFalse(method_exists($connection, 'setStatus'));
        $this->assertFalse(method_exists($connection, 'getDisctonnectedAt'));
        $this->assertFalse(method_exists($connection, 'setDisctonnectedAt'));
        $this->assertFalse(method_exists($connection, 'getConnectedAt'));
        $this->assertFalse(method_exists($connection, 'setConnectedAt'));
    }
}
