<?php

namespace Kitano\ConnectionBundle;

use Kitano\ConnectionBundle\Proxy\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionRepositoryInterface
{
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Model\Connection
     */
    public function update(Connection $connection);
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\ConnectionRepositoryInterface
     */
    public function destroy(Connection $connection);
    
    /**
     * @return \Kitano\ConnectionBundle\Model\Connection
     */
    public function createEmptyConnection();
}
