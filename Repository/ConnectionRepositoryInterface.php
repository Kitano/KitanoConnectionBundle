<?php

namespace Kitano\ConnectionBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @param mixed $connections ArrayCollection|ConnectionInterface
     *
     * @return mixed ArrayCollection|ConnectionInterface
     */
    public function update($connections);

    /**
     * @param mixed $connections ArrayCollection|ConnectionInterface
     * @return DoctrineMongoDBConnectionRepository
     */
    public function destroy($connections);

    /**
     * @return ConnectionInterface
     */
    public function createEmptyConnection();
}
