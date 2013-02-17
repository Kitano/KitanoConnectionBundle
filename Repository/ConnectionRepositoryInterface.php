<?php

namespace Kitano\ConnectionBundle\Repository;

use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionRepositoryInterface
{
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array());
    
    /**
     * @param ConnectionInterface $connection
     *
     * @return \Kitano\ConnectionBundle\Model\Connection
     */
    public function update(ConnectionInterface $connection);
    
    /**
     * @param ConnectionInterface $connection
     */
    public function destroy(ConnectionInterface $connection);
    
    /**
     * @return ConnectionInterface
     */
    public function createEmptyConnection();
}
