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
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @param array $filters
     * @return bool
     */
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $filters = array());

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
