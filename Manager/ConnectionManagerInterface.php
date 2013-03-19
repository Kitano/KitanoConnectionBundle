<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionManagerInterface
{
    /**
     * @param NodeInterface $source
     * @param NodeInterface $destination
     * @param string        $type
     *
     * @return ConnectionInterface
     */
    public function connect(NodeInterface $source, NodeInterface $destination, $type);

    /**
     * @param ConnectionInterface $connection
     *
     * @return ConnectionManagerInterface
     */
    public function disconnect(ConnectionInterface $connection);

    /**
     * Check if source node is connect to destination node or vice-versa.
     * 
     * @param NodeInterface $source
     * @param NodeInterface $destination
     * @param array         $filters
     *
     * @return boolean
     */
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $filters = array());

    /**
     * Check if source node is connect to destination node.
     * Take care of the orientation.
     * 
     * @param NodeInterface $source
     * @param NodeInterface $destination
     * @param array         $filters
     *
     * @return boolean
     */
    public function isConnectedTo(NodeInterface $source, NodeInterface $destination, array $filters = array());
    
    /**
     * @param NodeInterface $node
     * @param array         $filters
     *
     * @return boolean
     */
    public function hasConnections(NodeInterface $node, array $filters = array());

    /**
     * @param NodeInterface $node
     * @param array         $filters
     *
     * @return array
     */
    public function getConnectionsTo(NodeInterface $node, array $filters = array());

    /**
     * @param NodeInterface $node
     * @param array         $filters
     *
     * @return array
     */
    public function getConnectionsFrom(NodeInterface $node, array $filters = array());

    /**
     * @param NodeInterface $node
     * @param array         $filters
     *
     * @return array
     */
    public function getConnections(NodeInterface $node, array $filters = array());
}
