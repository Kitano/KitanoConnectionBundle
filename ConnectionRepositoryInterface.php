<?php

namespace Kitano\ConnectionBundle;

use Kitano\ConnectionBundle\Proxy\Connection;

class ConnectionRepositoryInterface
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
     * @param \Kitano\ConnectionBundle\Proxy\Connection $connection
     * @return \Kitano\ConnectionBundle\Proxy\Connection
     */
    public function connect(Connection $connection);
    
    /**
     * @param \Kitano\ConnectionBundle\Proxy\Connection $connection
     * @return \Kitano\ConnectionBundle\Proxy\Connection
     */
    public function disconnect(Connection $connection);
    
    /**
     * @param \Kitano\ConnectionBundle\Proxy\Connection $connection
     * @return \Kitano\ConnectionBundle\Entity\ConnectionRepository
     */
    public function destroy(Connection $connection);
}
