<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\DTO\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

class ConnectionManager
{
    /**
     * @var \Kitano\ConnectionBundle\Entity\ConnectionRepository
     */
    protected $connectionRepository;
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @return boolean
     */
    public function createConnection(NodeInterface $source, NodeInterface $destination)
    {
        $connection = new Connection();
        $connection->setSource($source);
        $connection->setDestination($destination);
        
        return $this->connect($connection);
    }
    
    public function connect(Connection $connection)
    {
        if(!$this->areConnected($connection->getSource(), $connection->getDestination())) {
            
        }
        
        return $this;
    }
    
    public function disconnect(Connection $connection)
    {
        return $this;
    }
    
    public function areConnected(NodeInterface $source, NodeInterface $destination)
    {
        return false;
    }
    
    public function getConnectionsTo(NodeInterface $value, array $filters = array())
    {
        return array();
    }
    
    public function getConnectionsFrom(NodeInterface $value, array $filters = array())
    {
        return array();
    }
    
    public function getConnections(NodeInterface $value, array $filters = array())
    {
        return array();
    }
}
