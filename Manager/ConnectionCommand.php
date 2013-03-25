<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Model\NodeInterface;

class ConnectionCommand
{
    protected $connections;

    public function __construct()
    {
        $this->connections = array();
    }

    public function addConnectCommand(NodeInterface $source, NodeInterface $destination, $type)
    {
        $this->connections[] = array(
            'source' => $source,
            'destination' => $destination,
            'type' => $type
        );
    }

    public function addDisconnectCommand(NodeInterface $source, NodeInterface $destination, $filters)
    {
        $this->connections[] = array(
            'source' => $source,
            'destination' => $destination,
            'filters' => $filters
        );
    }

    public function getConnections()
    {
        return $this->connections;
    }
}