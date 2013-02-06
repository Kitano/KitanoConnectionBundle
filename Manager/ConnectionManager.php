<?php

namespace Kitano\ConnectionBundle\Manager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Kitano\ConnectionBundle\Event\ConnectionEvent;

use Kitano\ConnectionBundle\Exception\AlreadyConnectedException;
use Kitano\ConnectionBundle\Exception\NotConnectedException;

use Kitano\ConnectionBundle\Model\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

class ConnectionManager
{
    protected $dispatcher;
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @return \Kitano\ConnectionBundle\Model\Connection
     */
    public function create(NodeInterface $source, NodeInterface $destination, $type)
    {
        $connection = new Connection();
        $connection->setSource($source);
        $connection->setDestination($destination);
        $connection->setType($type);
        
        return $this->connect($connection);
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function connect(Connection $connection)
    {
        if($this->areConnected($connection->getSource(), $connection->getDestination())) {
            throw new AlreadyConnectedException();
        }
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::CONNECTED, new ConnectionEvent(($connection)));
        }
        
        return $this;
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function disconnect(Connection $connection)
    {
        if(!$this->areConnected($connection->getSource(), $connection->getDestination())) {
            throw new NotConnectedException();
        }
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::DISCONNECTED, new ConnectionEvent(($connection)));
        }
        
        return $this;
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @param type $type
     * @return boolean
     */
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $types = array())
    {
        return false;
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $value
     * @param type $type
     * @param array $filters
     * @return type
     */
    public function getConnectionsTo(NodeInterface $value, array $types = array(), array $filters = array())
    {
        return array();
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $value
     * @param type $type
     * @param array $filters
     * @return type
     */
    public function getConnectionsFrom(NodeInterface $value, array $types = array(), array $filters = array())
    {
        return array();
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $value
     * @param type $type
     * @param array $filters
     * @return type
     */
    public function getConnections(NodeInterface $value, array $types = array(), array $filters = array())
    {
        return array();
    }
    
    public function setDispatch(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    public function getDispatch()
    {
        return $this->dispatcher;
    }
}
