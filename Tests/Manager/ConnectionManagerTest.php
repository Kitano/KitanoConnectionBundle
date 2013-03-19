<?php

namespace Kitano\ConnectionBundle\Tests\Manager;

use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;
use Kitano\ConnectionBundle\Manager\ConnectionManager;
use Kitano\ConnectionBundle\Repository\ArrayConnectionRepository;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Model\Connection';
    const CONNECTION_TYPE = 'follow';

    /**
     * @var \Kitano\ConnectionBundle\Manager\ConnectionManager
     */
    private $connectionManager;

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

    public function testRemoveCreateAndDestroyMethods()
    {
        $this->assertFalse(method_exists($this->connectionManager, "create"));
        $this->assertFalse(method_exists($this->connectionManager, "destroy"));
    }

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

    public function testGetConnectionsFrom()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();

        $connection = $this->connectionManager->connect($nodeSource, $nodeDestination, self::CONNECTION_TYPE);

        $connections = $this->connectionManager->getConnectionsFrom($nodeSource, $this->getFilters());

        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections);
    }

    public function testGetConnectionsTo()
    {
        $nodeSource = new Node();
        $nodeDestination = new Node();

        $connection = $this->connectionManager->connect($nodeSource, $nodeDestination, self::CONNECTION_TYPE);

        $connections = $this->connectionManager->getConnectionsTo($nodeDestination, $this->getFilters());

        $this->assertNotNull($connections);
        $this->assertContains($connection, $connections);
    }

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
}
