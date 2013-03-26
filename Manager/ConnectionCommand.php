<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\Model\NodeInterface;

class ConnectionCommand
{
    protected $commands;

    public function __construct()
    {
        $this->commands = array();
    }

    public function addConnectCommand(NodeInterface $source, NodeInterface $destination, $type)
    {
        $this->commands[] = array(
            'source' => $source,
            'destination' => $destination,
            'type' => $type
        );
    }

    public function addDisconnectCommand(NodeInterface $source, NodeInterface $destination, $filters)
    {
        $this->commands[] = array(
            'source' => $source,
            'destination' => $destination,
            'filters' => $filters
        );
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }
}