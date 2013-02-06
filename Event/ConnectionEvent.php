<?php

namespace Kitano\ConnectionBundle\Event;

use Symfony\Component\EventDispatcher\Event as EventBase;

use Kitano\ConnectionBundle\Model\Connection;

class ConnectionEvent extends EventBase {
    const CONNECTED = "kitano.connection.event.connected";
    const DISCONNECTED = "kitano.connection.event.disconnected";
    
    private $connection;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}
