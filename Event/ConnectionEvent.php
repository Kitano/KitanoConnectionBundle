<?php

namespace Kitano\ConnectionBundle\Event;

use Symfony\Component\EventDispatcher\Event as EventBase;

use Kitano\ConnectionBundle\Model\ConnectionInterface;

class ConnectionEvent extends EventBase
{
    const CONNECTED = "kitano.connection.event.connected";
    const DISCONNECTED = "kitano.connection.event.disconnected";

    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
