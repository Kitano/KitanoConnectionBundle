<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Model\NodeInterface;

class ConnectionCommand
{
    /**
     * @var array
     */
    protected $connectCommands;
    
    /**
     * @var array
     */
    protected $disconnectCommands;

    public function __construct()
    {
        $this->connectCommands = array();
        $this->disconnectCommands = array();
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @param string $type
     */
    public function addConnectCommand(NodeInterface $source, NodeInterface $destination, $type)
    {
        if(!is_string($type)) {
            throw new \InvalidArgumentException('type must be a string');
        }
        
        $this->connectCommands[] = array(
            'source' => $source,
            'destination' => $destination,
            'type' => $type
        );
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $source
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $destination
     * @param array $filters
     */
    public function addDisconnectCommand(NodeInterface $source, NodeInterface $destination, array $filters = array())
    {
        $this->disconnectCommands[] = array(
            'source' => $source,
            'destination' => $destination,
            'filters' => $filters
        );
    }

    /**
     * @return array
     */
    public function getConnectCommands()
    {
        return $this->connectCommands;
    }

    /**
     * @return array
     */
    public function getDisconnectCommands()
    {
        return $this->disconnectCommands;
    }
}