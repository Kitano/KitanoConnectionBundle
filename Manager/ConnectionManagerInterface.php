<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Model\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionManagerInterface
{
    public function create(NodeInterface $source, NodeInterface $destination, $type);
    
    public function destroy(Connection $connection);
    
    public function connect(Connection $connection);
    
    public function disconnect(Connection $connection);
    
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $filters = array());
    
    public function hasConnections(NodeInterface $value, array $filters = array());
    
    public function getConnectionsTo(NodeInterface $value, array $filters = array());
    
    public function getConnectionsFrom(NodeInterface $value, array $filters = array());
            
    public function getConnections(NodeInterface $value, array $filters = array());
}
