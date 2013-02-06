<?php

namespace Kitano\ConnectionBundle\Manager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Kitano\ConnectionBundle\ConnectionRepositoryInterface;

use Kitano\ConnectionBundle\Event\ConnectionEvent;

use Kitano\ConnectionBundle\Exception\AlreadyConnectedException;
use Kitano\ConnectionBundle\Exception\NotConnectedException;

use Kitano\ConnectionBundle\Model\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

class ConnectionManager
{
    /**
     * @var \Kitano\ConnectionBundle\ConnectionRepositoryInterface
     */
    protected $connectionRepository;
    
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
        
        $this->getConnectionRepository()->connect($connection);
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::CONNECTED, new ConnectionEvent(($connection)));
        }
        
        return $connection;
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
        
        $this->getConnectionRepository()->connect($connection);
        
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
        
        $this->getConnectionRepository()->disconnect($connection);
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::DISCONNECTED, new ConnectionEvent(($connection)));
        }
        
        return $this;
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $value
     * @param array $types
     * @param array $filters
     */
    public function hasConnections(NodeInterface $value, array $types = array(), array $filters = array())
    {
        return count($this->getConnections($value, $types, $filters) > 0);
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
        return $this->getConnectionRepository()->getConnectionsWithDestination($value);
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
        return $this->getConnectionRepository()->getConnectionsWithSource($value);
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
        return array_merge(
            $this->getConnectionsFrom($value, $types, $filters),
            $this->getConnectionsTo($value, $types, $filters)
        );
    }
    
    public function setDispatch(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    public function getDispatch()
    {
        return $this->dispatcher;
    }
    
    public function setConnectionRepository(ConnectionRepositoryInterface $connectionRepository)
    {
        $this->connectionRepository = $connectionRepository;
    }
    
    public function getConnectionRepository()
    {
        return $this->connectionRepository;
    }
}
