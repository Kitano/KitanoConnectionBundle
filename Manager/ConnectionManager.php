<?php

namespace Kitano\ConnectionBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Kitano\ConnectionBundle\ConnectionRepositoryInterface;

use Kitano\ConnectionBundle\Event\ConnectionEvent;

use Kitano\ConnectionBundle\Exception\AlreadyConnectedException;
use Kitano\ConnectionBundle\Exception\NotConnectedException;

use Kitano\ConnectionBundle\Proxy\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

class ConnectionManager
{
    /**
     * @var \Kitano\ConnectionBundle\ConnectionRepositoryInterface
     */
    protected $connectionRepository;
    
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @return \Kitano\ConnectionBundle\Model\Connection
     */
    public function create(NodeInterface $source, NodeInterface $destination, $type)
    {
        if($this->areConnected($source, $destination)) {
            throw new AlreadyConnectedException();
        }
        
        $connection = $this->getConnectionRepository()->createEmptyConnection();
        $connection->setSource($source);
        $connection->setDestination($destination);
        $connection->setType($type);
        $connection->connect();
        
        $this->getConnectionRepository()->update($connection);
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::CONNECTED, new ConnectionEvent(($connection)));
        }
        
        return $connection;
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function destroy(Connection $connection)
    {
        if(!$this->areConnected($connection->getSource(), $connection->getDestination())) {
            throw new NotConnectedException();
        }
        
        $connection->disconnect();
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::DISCONNECTED, new ConnectionEvent(($connection)));
        }
        
        $this->getConnectionRepository()->destroy($connection);
        
        return $this;
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function connect(Connection $connection)
    {
        if($this->areConnected($connection->getSource(), $connection->getDestination())) {
            throw new AlreadyConnectedException();
        }
        
        $connection->connect();
        
        $this->getConnectionRepository()->update($connection);
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::CONNECTED, new ConnectionEvent(($connection)));
        }
        
        return $this;
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function disconnect(Connection $connection)
    {
        if(!$this->areConnected($connection->getSource(), $connection->getDestination())) {
            throw new NotConnectedException();
        }
        
        $connection->disconnect();
        
        $this->getConnectionRepository()->update($connection);
        
        if($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::DISCONNECTED, new ConnectionEvent(($connection)));
        }
        
        return $this;
    }
 
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @param array $filters
     * @return boolean
     */
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $filters = array())
    {
        return false;
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return boolean
     */
    public function hasConnections(NodeInterface $node, array $filters = array())
    {
        return count($this->getConnections($node, $filters) > 0);
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsTo(NodeInterface $node, array $filters = array())
    {
        return $this->getConnectionRepository()->getConnectionsWithDestination($node, $filters);
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsFrom(NodeInterface $node, array $filters = array())
    {
        return $this->getConnectionRepository()->getConnectionsWithSource($node, $filters);
    }
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $value
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnections(NodeInterface $node, array $filters = array())
    {
        $connectionsFrom = $this->getConnectionsFrom($node, $filters);
        $connectionsTo = $this->getConnectionsTo($node, $filters);
        
        if(null === $connectionsFrom && null === $connectionsTo) {
            return null;
        }
        else {
            return new ArrayCollection(array_merge((array) $connectionsFrom, (array) $connectionsTo));
        }
    }
    
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function getDispatch()
    {
        return $this->dispatcher;
    }
    
    /**
     * @param \Kitano\ConnectionBundle\ConnectionRepositoryInterface $connectionRepository
     */
    public function setConnectionRepository(ConnectionRepositoryInterface $connectionRepository)
    {
        $this->connectionRepository = $connectionRepository;
    }
    
    /**
     * @return \Kitano\ConnectionBundle\ConnectionRepositoryInterface $connectionRepository
     */
    public function getConnectionRepository()
    {
        return $this->connectionRepository;
    }
}
