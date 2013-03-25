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
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $nodeA
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $nodeB
     * @param array $filters
     * @return bool
     */
    public function areConnected(NodeInterface $nodeA, NodeInterface $nodeB, array $filters = array());

    /**
     * @param mixed $connections array|ConnectionInterface
     *
     * @return mixed array|ConnectionInterface
     */
    public function update($connections);

    /**
     * @param mixed $connections array|ConnectionInterface
     * @return DoctrineMongoDBConnectionRepository
     */
    public function destroy($connections);

    /**
     * @return ConnectionInterface
     */
    public function createEmptyConnection();
}
