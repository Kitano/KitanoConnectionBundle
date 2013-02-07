<?php

namespace Kitano\ConnectionBundle;

use Kitano\ConnectionBundle\Proxy\Connection;

class ConnectionRepositoryInterface
{
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return type
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return type
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
