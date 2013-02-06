<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\DTO\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionManagerInterface
{
    public function create(NodeInterface $source, NodeInterface $destination);
    
    public function connect(Connection $connection);
    
    public function disconnect(Connection $connection);
    
    public function areConnected(NodeInterface $source, NodeInterface $destination);
    
    public function getConnectionsTo(NodeInterface $value, array $filters = array());
    
    public function getConnectionsFrom(NodeInterface $value, array $filters = array());
            
    public function getConnections(NodeInterface $value, array $filters = array());
}
