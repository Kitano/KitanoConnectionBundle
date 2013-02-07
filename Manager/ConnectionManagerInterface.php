<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Model\Connection;
use Kitano\ConnectionBundle\Model\NodeInterface;

interface ConnectionManagerInterface
{
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @return \Kitano\ConnectionBundle\Model\Connection
     */
    public function create(NodeInterface $source, NodeInterface $destination, $type);
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function destroy(Connection $connection);
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function connect(Connection $connection);
    
    /**
     * @param \Kitano\ConnectionBundle\Model\Connection $connection
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    public function disconnect(Connection $connection);
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @param array $filters
     * @return boolean
     */
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return boolean
     */
    public function hasConnections(NodeInterface $node, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsTo(NodeInterface $node, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnectionsFrom(NodeInterface $node, array $filters = array());
    
    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $value
     * @param array $filters
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getConnections(NodeInterface $node, array $filters = array());
}
