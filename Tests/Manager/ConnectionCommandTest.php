<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Manager\ConnectionCommand;
use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;

class ConnectionCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group manager
     */
    public function testPrototype()
    {
        $connectionCommand = new ConnectionCommand();
        
        $this->assertTrue(method_exists($connectionCommand, 'getConnectCommands'));
        $this->assertTrue(method_exists($connectionCommand, 'getDisconnectCommands'));
        $this->assertTrue(method_exists($connectionCommand, 'addConnectCommand'));
        $this->assertTrue(method_exists($connectionCommand, 'addDisconnectCommand'));
    }
    
    public static function wrongType()
    {
        return array(
            array(42),
            array(42.5),
            array(new \stdClass()),
            array(array()),
        );
    }
    
    /**
     * @dataProvider wrongType
     * @expectedException \InvalidArgumentException
     */
    public function testAddConnectWrongType($type)
    {
        $connectionCommand = new ConnectionCommand();
        $connectionCommand->addConnectCommand(new Node(), new Node(), $type);
    }
    
    /**
     * @group manager
     * @depends testPrototype
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

        foreach($connectionCommand->getConnectCommands() as $i => $command)
        {
            $this->assertEquals($nodes[$i]['source'], $command['source']);
            $this->assertEquals($nodes[$i]['destination'], $command['destination']);
            $this->assertEquals($nodes[$i]['type'], $command['type']);
        }
    }

    /**
     * @group manager
     * @depends testPrototype
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

        foreach($connectionCommand->getDisconnectCommands() as $i => $command)
        {
            $this->assertEquals($nodes[$i]['source'], $command['source']);
            $this->assertEquals($nodes[$i]['destination'], $command['destination']);
            $this->assertEquals($nodes[$i]['filters'], $command['filters']);
        }
    }
}