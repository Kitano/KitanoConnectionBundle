<?php

namespace Kitano\ConnectionBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;

use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\NodeInterface;

/**
 * ConnectionRepository
 */
class ArrayConnectionRepository implements ConnectionRepositoryInterface
{
    protected $class;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $connections;

    public function __construct($class)
    {
        $this->class = $class;
        $this->connections = new ArrayCollection();
    }
    
    /**
     * @param NodeInterface $node
     * @param array         $filters
     *
     * @return array
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array())
    {
        $connections = array();
        
        foreach ($this->connections as $connection) {
            if($node === $connection->getSource()) {
                if(array_key_exists('type', $filters) && $connection->getType() === $filters['type']) {
                    $connections[] = $connection;
                }
            }
        }
        
        return $connections;
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     *
     * @return array
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array())
    {
        $connections = array();
        
        foreach ($this->connections as $connection) {
            if($node === $connection->getDestination()) {
                if(array_key_exists('type', $filters) && $connection->getType() === $filters['type']) {
                    $connections[] = $connection;
                }
            }
        }
        
        return $connections;
    }
    
    /**
     * @param ConnectionInterface $connection
     *
     * @return ConnectionInterface
     */
    public function update(ConnectionInterface $connection)
    {
        if(!$this->connections->contains($connection)) {
            $this->connections->add($connection);
        }
        
        return $connection;
    }
    
    /**
     * @param ConnectionInterface $connection
     *
     * @return ConnectionRepositoryInterface
     */
    public function destroy(ConnectionInterface $connection)
    {
        if($this->connections->contains($connection)) {
            $this->connections->removeElement($connection);
        }
        
        return $this;
    }
    
    /**
     * @return ConnectionInterface
     */
    public function createEmptyConnection()
    {
        return new $this->class();
    }
}
