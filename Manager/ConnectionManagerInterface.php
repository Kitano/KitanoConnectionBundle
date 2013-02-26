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
    public function create(NodeInterface $source, NodeInterface $destination, $type);
    
    /**
     * @param ConnectionInterface $connection
     * 
     * @return ConnectionManagerInterface
     */
    public function destroy(ConnectionInterface $connection);
    
    /**
     * @param ConnectionInterface $connection
     * 
     * @return ConnectionManagerInterface
     */
    public function connect(ConnectionInterface $connection);
    
    /**
     * @param ConnectionInterface $connection
     * 
     * @return ConnectionManagerInterface
     */
    public function disconnect(ConnectionInterface $connection);
    
    /**
     * @param NodeInterface $source
     * @param NodeInterface $destination
     * @param array         $filters
     *
     * @return boolean
     */
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $filters = array());
    
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
