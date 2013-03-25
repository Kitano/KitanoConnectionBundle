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
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array                                        $filters
     *
     * @return array
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
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return array|void
     */
    public function getConnections(NodeInterface $node, array $filters = array())
    {
        $connections = new ArrayCollection();

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
        $connections = new ArrayCollection();

        foreach ($this->connections as $connection) {
            if(array_key_exists('type', $filters)) {
                if($connection->getType() != $filters['type']) {
                    continue;
                }
            }
            if ($nodeA === $connection->getSource() && $nodeB === $connection->getDestination()) {
                $connections[] = $connection;
            }
        }

        return ($connections->count() > 0) ? true : false;
    }

    /**
     * @param mixed $connections ArrayCollection|ConnectionInterface
     *
     * @return mixed ArrayCollection|ConnectionInterface
     */
    public function update($connections)
    {
        if($connections instanceof ArrayCollection) {
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
     * @param mixed $connections ArrayCollection|ConnectionInterface
     * @return DoctrineMongoDBConnectionRepository
     */
    public function destroy($connections)
    {
        if($connections instanceof ArrayCollection) {
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
     * @return ConnectionInterface
     */
    public function createEmptyConnection()
    {
        return new $this->class();
    }
}
