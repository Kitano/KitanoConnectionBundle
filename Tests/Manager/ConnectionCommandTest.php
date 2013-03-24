<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Manager\ConnectionCommand;
use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;

class ConnectionCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group manager
     */
    public function testAddConnectCommand()
    {
        $connectionCommand = new ConnectionCommand();
        $nodes = array();

        for($i=0; $i<=3; $i++) {
            $nodes[$i]['source'] = new Node();
            $nodes[$i]['destination'] = new Node();
            $nodes[$i]['type'] = 'like';
            $connectionCommand->addConnectCommand($nodes[$i]['source'], $nodes[$i]['destination'], $nodes[$i]['type']);
        }

        foreach($connectionCommand->getConnections() as $i => $node)
        {
            $this->assertEquals($nodes[$i]['source'], $node['source']);
            $this->assertEquals($nodes[$i]['destination'], $node['destination']);
            $this->assertEquals($nodes[$i]['type'], $node['type']);
        }
    }

    /**
     * @group manager
     */
    public function testAddDisconnectCommand()
    {
        $connectionCommand = new ConnectionCommand();
        $nodes = array();

        for($i=0; $i<=3; $i++) {
            $nodes[$i]['source'] = new Node();
            $nodes[$i]['destination'] = new Node();
            $nodes[$i]['filters'] = array('type' => 'like');
            $connectionCommand->addDisconnectCommand($nodes[$i]['source'], $nodes[$i]['destination'], $nodes[$i]['filters']);
        }

        foreach($connectionCommand->getConnections() as $i => $node)
        {
            $this->assertEquals($nodes[$i]['source'], $node['source']);
            $this->assertEquals($nodes[$i]['destination'], $node['destination']);
            $this->assertEquals($nodes[$i]['filters'], $node['filters']);
        }
    }
}