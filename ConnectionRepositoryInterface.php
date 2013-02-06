<?php

namespace Kitano\ConnectionBundle;

use Kitano\ConnectionBundle\Proxy\Connection;

class ConnectionRepositoryInterface
{
    public function getConnectionsWithSource($objectClass, $objectId);
    public function getConnectionsWithDestination($objectClass, $objectId);
    public function connect(Connection $connection);
    public function disconnect(Connection $connection);
}
