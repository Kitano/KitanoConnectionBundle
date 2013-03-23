<?php

namespace Kitano\ConnectionBundle\Repository;

use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionRepositoryInterface
{
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array                                        $filters
     *
     * @return array
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array());

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array                                        $filters
     *
     * @return array
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array());

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     *
     * @return array
     */
    public function getConnections(NodeInterface $node, array $filters = array());

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node1
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node2
     * @param array $filters
     * @return array
     */
    public function areConnected(NodeInterface $node1, NodeInterface $node2, array $filters = array());

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
