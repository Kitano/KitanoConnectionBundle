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
     * {@inheritDoc}
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array())
    {
        $connections = array();

        foreach ($this->connections as $connection) {
            if(array_key_exists('type', $filters)) {
                if($connection->getType() != $filters['type']) {
                    continue;
                }
            }

            if ($node === $connection->getSource()) {
                $connections[] = $connection;
            }
        }

        return $connections;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array())
    {
        $connections = array();

        foreach ($this->connections as $connection) {
            if(array_key_exists('type', $filters)) {
                if($connection->getType() != $filters['type']) {
                    continue;
                }
            }

            if ($node === $connection->getDestination()) {
                $connections[] = $connection;
            }
        }

        return $connections;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnections(NodeInterface $node, array $filters = array())
    {
        $connections = array();

        foreach ($this->connections as $connection) {
            if(array_key_exists('type', $filters)) {
                if($connection->getType() != $filters['type']) {
                    continue;
                }
            }

            if ($node === $connection->getDestination()) {
                $connections[] = $connection;
            }

            if ($node === $connection->getSource()) {
                $connections[] = $connection;
            }
        }

        return $connections;
    }

    /**
     * {@inheritDoc}
     */
    public function areConnected(NodeInterface $nodeA, NodeInterface $nodeB, array $filters = array())
    {
        foreach ($this->connections as $connection) {
            if(array_key_exists('type', $filters) && $connection->getType() !== $filters['type']) {
                continue;
            }
            
            if ($nodeA === $connection->getSource() && $nodeB === $connection->getDestination()) {
                return true;
            }
            
            if ($nodeB === $connection->getSource() && $nodeA === $connection->getDestination()) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function update($connections)
    {
        if(is_array($connections)) {
            foreach($connections as $connection) {
                $this->persistConnection($connection);
            }
        } else {
            $this->persistConnection($connections);
        }

        return $connections;
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\ConnectionInterface $connection
     */
    protected function persistConnection(ConnectionInterface $connection)
    {
        if (!$this->connections->contains($connection)) {
            $this->connections->add($connection);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function destroy($connections)
    {
        if(is_array($connections)) {
            foreach($connections as $connection) {
                $this->removeConnection($connection);
            }
        } else {
            $this->removeConnection($connections);
        }

        return $this;
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\ConnectionInterface $connection
     */
    protected function removeConnection(ConnectionInterface $connection)
    {
        $this->connections->removeElement($connection);
    }

    /**
     * {@inheritDoc}
     */
    public function createEmptyConnection()
    {
        return new $this->class();
    }
}
