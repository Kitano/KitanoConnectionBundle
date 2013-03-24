<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;
use Kitano\ConnectionBundle\Manager\ConnectionManager;
use Kitano\ConnectionBundle\Repository\ArrayConnectionRepository;
use Doctrine\Common\Collections\ArrayCollection;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Model\Connection';
    const CONNECTION_TYPE = 'follow';

    /**
     * @var \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    private $connectionManager;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $nodes;

    public function setUp()
    {
        $this->connectionManager = new ConnectionManager();
        $this->connectionManager->setFilterValidator($this->getFilterValidatorMock());
        $this->connectionManager->setConnectionRepository(new ArrayConnectionRepository(self::CONNECTION_CLASS));
    }

    protected function getFilterValidatorMock()
    {
        $mock = $this->getMockBuilder('Kitano\ConnectionBundle\Manager\FilterValidator')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    protected function generateNodes()
    {
        $this->nodes = new ArrayCollection();

        for($i=0; $i<=3; $i++) {
            $node['source'] = new Node($i);
            $node['destination'] = new Node($i);

            $this->nodes->add($node);
        }
    }

    protected function getConnectionCommandMock($type)
    {
        foreach($this->nodes as $i => $node)
        {
            switch($type)
            {
                case 'connect' :
                    $node['type'] = 'like';
                    break;

                case 'disconnect' :
                    $node['filters'] = array('type' => 'like');
                    break;
            }

            $this->nodes[$i] = $node;
        }

        $mock = $this->getMockBuilder('Kitano\ConnectionBundle\Manager\ConnectionCommand')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getConnections')
            ->will($this->returnValue($this->nodes))
        ;

        return $mock;
    }

    protected function getFilters()
    {
        return array (
            'type' => self::CONNECTION_TYPE,
        );
    }

    public function tearDown()
    {
        unset($this->connectionManager);
    }

    /**
     * @group manager
     */
    public function testRemoveCreateAndDestroyMethods()
    {
        $this->assertFalse(method_exists($this->connectionManager, "create"));
        $this->assertTrue(method_exists($this->connectionManager, "destroy"));
    }

    /**
     * @group manager
     */
    public function testConnect()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();

        $connection = $this->connectionManager->connect($nodeSource, $nodeDestination, self::CONNECTION_TYPE);

        $this->assertInstanceOf('Kitano\ConnectionBundle\Model\Connection', $connection);
        $this->assertEquals($nodeSource, $connection->getSource());
        $this->assertEquals($nodeDestination, $connection->getDestination());
        $this->assertEquals("follow", $connection->getType());
    }

    /**
     * @group manager
     */
    public function testGetConnectionsFrom()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();

        $connection = $this->connectionManager->connect($nodeSource, $nodeDestination, self::CONNECTION_TYPE);

        $connections = $this->connectionManager->getConnectionsFrom($nodeSource, $this->getFilters());

        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections);
    }

    /**
     * @group manager
     */
    public function testGetConnectionsTo()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();

        $connection = $this->connectionManager->connect($nodeSource, $nodeDestination, self::CONNECTION_TYPE);

        $connections = $this->connectionManager->getConnectionsTo($nodeDestination, $this->getFilters());

        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections);
    }

    /**
     * @group manager
     */
    public function testGetConnections()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();

        $connection = $this->connectionManager->connect($nodeSource, $nodeDestination, self::CONNECTION_TYPE);

        $connectionsOnA = $this->connectionManager->getConnections($nodeSource, $this->getFilters());
        $connectionsOnB = $this->connectionManager->getConnections($nodeDestination, $this->getFilters());

        $this->assertNotNull($connectionsOnA);
        $this->assertContains($connection, $connectionsOnA->getIterator());

        $this->assertNotNull($connectionsOnB);
        $this->assertContains($connection, $connectionsOnB->getIterator());
    }

    /**
     * @group manager
     */
    public function testAreConnectedSuccess()
    {
        $nodeA = new Node();
        $nodeB = new Node();
        $nodeC = new Node();
        $nodeD = new Node();

        $this->connectionManager->connect($nodeA, $nodeB, "follow");
        $this->connectionManager->connect($nodeA, $nodeC, "like");
        $this->connectionManager->connect($nodeA, $nodeD, "view");

        $this->assertTrue($this->connectionManager->areConnected($nodeA, $nodeB, array('type' => 'follow')));
        $this->assertFalse($this->connectionManager->areConnected($nodeB, $nodeA, array('type' => 'follow')));
        $this->assertFalse($this->connectionManager->areConnected($nodeA, $nodeC, array('type' => 'follow')));
        $this->assertFalse($this->connectionManager->areConnected($nodeC, $nodeA, array('type' => 'like')));
    }

    /**
     * @group manager
     */
    public function testIsConnectedToSuccess()
    {
        $this->assertTrue(method_exists($this->connectionManager, 'isConnectedTo'));
        
        $nodeA = new Node();
        $nodeB = new Node();
        $nodeC = new Node();

        $this->connectionManager->connect($nodeA, $nodeB, "follow");
        $this->connectionManager->connect($nodeA, $nodeC, "like");

        $this->assertTrue($this->connectionManager->isConnectedTo($nodeA, $nodeB, array('type' => 'follow')));
        $this->assertFalse($this->connectionManager->isConnectedTo($nodeB, $nodeA, array('type' => 'follow')));
        
        $this->assertFalse($this->connectionManager->isConnectedTo($nodeA, $nodeB, array('type' => 'like')));
        $this->assertFalse($this->connectionManager->isConnectedTo($nodeB, $nodeA, array('type' => 'like')));
    }

    /**
     * @group manager
     */
    public function testDisconnectOneConnection()
    {
        $nodeA = new Node();
        $nodeB = new Node();

        $this->connectionManager->connect($nodeA, $nodeB, "follow");
        $this->connectionManager->disconnect($nodeA, $nodeB, array('type' => 'follow'));

        $this->assertFalse($this->connectionManager->isConnectedTo($nodeA, $nodeB, array('type' => 'follow')));
    }

    /**
     * @group manager
     */
    public function testDisconnectMultipleConnections()
    {
        $nodeA = new Node();
        $nodeB = new Node();
        $nodeC = new Node();

        $this->connectionManager->connect($nodeA, $nodeB, "like");
        $this->connectionManager->connect($nodeA, $nodeB, "share");
        $this->connectionManager->connect($nodeA, $nodeC, "like");
        $this->connectionManager->disconnect($nodeA, $nodeB);

        $this->assertFalse($this->connectionManager->isConnectedTo($nodeA, $nodeB));
        $this->assertTrue($this->connectionManager->isConnectedTo($nodeA, $nodeC));
    }

    /**
     * @group manager
     * @expectedException \Kitano\ConnectionBundle\Exception\NotConnectedException
     */
    public function testDisconnectThrowException()
    {
        $nodeA = new Node();
        $nodeB = new Node();

        $this->connectionManager->disconnect($nodeA, $nodeB);
    }

    /**
     * @group manager
     */
    public function testDestroy()
    {
        $nodeA = new Node();
        $nodeB = new Node();

        $connection = $this->connectionManager->connect($nodeA, $nodeB, "like");

        $this->connectionManager->destroy($connection);

        $this->assertFalse($this->connectionManager->isConnectedTo($nodeA, $nodeB));
    }

    /**
     * @group manager
     */
    public function testConnectBulk()
    {
        $this->generateNodes();

        $connectionCommand = $this->getConnectionCommandMock('connect');

        $this->connectionManager->connectBulk($connectionCommand);

        foreach($connectionCommand->getConnections() as $connection)
        {
            $this->assertTrue($this->connectionManager->areConnected($connection['source'], $connection['destination'], array('type' => $connection['type'])));
        }
    }

    /**
     * @group manager
     */
    public function testDisconnectBulk()
    {
        $this->generateNodes();

        $connectionCommand = $this->getConnectionCommandMock('connect');
        $this->connectionManager->connectBulk($connectionCommand);

        $connectionCommand = $this->getConnectionCommandMock('disconnect');
        $this->connectionManager->disconnectBulk($connectionCommand);

        foreach($connectionCommand->getConnections() as $connection)
        {
            $this->assertFalse($this->connectionManager->areConnected($connection['source'], $connection['destination'], $connection['filters']));
        }
    }
}
