<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Model\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionManagerInterface
{
    public function create(NodeInterface $source, NodeInterface $destination, $type);
    
    public function connect(Connection $connection);
    
    public function disconnect(Connection $connection);
    
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $types = array());
    
    public function getConnectionsTo(NodeInterface $value, array $types = array(), array $filters = array());
    
    public function getConnectionsFrom(NodeInterface $value, array $types = array(), array $filters = array());
            
    public function getConnections(NodeInterface $value, array $types = array(), array $filters = array());
}
