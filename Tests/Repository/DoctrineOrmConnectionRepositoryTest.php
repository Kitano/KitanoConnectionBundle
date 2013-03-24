<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

use Doctrine\Common\Collections\ArrayCollection;

use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node;
use Kitano\ConnectionBundle\Tests\OrmTestCase;
use Kitano\ConnectionBundle\Repository\DoctrineOrmConnectionRepository;
use Kitano\ConnectionBundle\Model\NodeInterface;

class DoctrineOrmConnectionRepositoryTest extends OrmTestCase implements ConnectionRepositoryTestInterface
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Entity\Connection';
    const CONNECTION_TYPE = 'follow';

    /**
     * @var \Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface
     */
    private $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        parent::setUp();

        $this->repository = new DoctrineOrmConnectionRepository($this->getEntityManager(), static::CONNECTION_CLASS);
    }

    public function tearDown()
    {
        unset($this->repository);

        parent::tearDown();
    }

    /**
     * @group orm
     */
    public function testCreateEmptyConnectionReturn()
    {
        $connection = $this->repository->createEmptyConnection();

        $this->assertInstanceOf(static::CONNECTION_CLASS, $connection);
    }

    /**
     * @group orm
     */
    public function testExtractedClassMetadata()
    {
        $node = new Node();
        $node->setId(123);
        $expectedMetadata = array(
            'object_class' => 'Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity\Node',
            'object_id' => 123,
        );

        $method = new \ReflectionMethod($this->repository, 'extractMetadata');
        $method->setAccessible(true);
        $metadata = $method->invoke($this->repository, $node);

        $this->assertEquals($metadata, $expectedMetadata);
    }

    protected function createConnection(NodeInterface $nodeSource, NodeInterface $nodeDestination)
    {
        $connection = $this->repository->createEmptyConnection();

        $connection->setSource($nodeSource);
        $connection->setDestination($nodeDestination);
        $connection->setType(self::CONNECTION_TYPE);

        return $connection;
    }

    /**
     * @group orm
     */
    public function testUpdate()
    {
        $connection = $this->createConnection(new Node(42), new Node(123));

        $this->assertEquals($connection, $this->repository->update($connection));
        $this->assertEquals($connection, $this->getEntityManager()->find(self::CONNECTION_CLASS, $connection->getId()));
    }

    /**
     * @group orm
     */
    public function testDestroy()
    {
        $connection = $this->createConnection(new Node(42), new Node(123));

        $this->assertEquals($connection, $this->repository->update($connection));

        $id = $connection->getId();

        $this->assertEquals($this->repository, $this->repository->destroy(new ArrayCollection(array($connection))));
        $this->assertNull($this->getEntityManager()->find(self::CONNECTION_CLASS, $id));
    }

    /**
     * @group orm
     */
    public function testGetConnectionsWithSource()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithSource($nodeSource));
    }

    /**
     * @group orm
     */
    public function testGetConnectionsWithSourceNotContains()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();

        //TODO: Check getConnectionsWithSource return an array
        $this->assertEquals(array(), $this->repository->getConnectionsWithSource($nodeSource));
    }

    /**
     * @group orm
     */
    public function testGetConnectionsWithDestination()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();

        $connection = $this->createConnection($nodeSource, $nodeDestination);

        $this->repository->update($connection);

        $this->assertContains($connection, $this->repository->getConnectionsWithDestination($nodeDestination));
    }

    /**
     * @group orm
     */
    public function testGetConnectionsWithDestinationNotContains()
    {
        $nodeSource = new Node(42);
        $nodeDestination = new Node(123);

        $this->getEntityManager()->persist($nodeSource);
        $this->getEntityManager()->persist($nodeDestination);
        $this->getEntityManager()->flush();

        $this->assertEquals(array(), $this->repository->getConnectionsWithDestination($nodeDestination));
    }

    /**
     * @group orm
     */
    public function testAreConnected()
    {
        $node1 = new Node(455);
        $node2 = new Node(4412);
        $node3 = new Node(4244);

        $this->getEntityManager()->persist($node1);
        $this->getEntityManager()->persist($node2);
        $this->getEntityManager()->persist($node3);
        $this->getEntityManager()->flush();

        $connection1 = $this->createConnection($node1, $node2);
        $connection2 = $this->createConnection($node2, $node1);
        $connection3 = $this->createConnection($node1, $node3);

        $this->repository->update($connection1);
        $this->repository->update($connection2);
        $this->repository->update($connection3);

        $this->assertTrue($this->repository->areConnected($node1, $node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertTrue($this->repository->areConnected($node2, $node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertTrue($this->repository->areConnected($node1, $node3, array('type' => self::CONNECTION_TYPE)));
        $this->assertFalse($this->repository->areConnected($node2, $node3, array('type' => self::CONNECTION_TYPE)));
    }

    /**
     * @group orm
     */
    public function testGetConnections()
    {
        $node1 = new Node(455);
        $node2 = new Node(4412);
        $node3 = new Node(4244);

        $this->getEntityManager()->persist($node1);
        $this->getEntityManager()->persist($node2);
        $this->getEntityManager()->persist($node3);
        $this->getEntityManager()->flush();

        $connection1 = $this->createConnection($node1, $node2);
        $connection2 = $this->createConnection($node2, $node1);
        $connection3 = $this->createConnection($node1, $node3);

        $this->repository->update($connection1);
        $this->repository->update($connection2);
        $this->repository->update($connection3);

        $this->assertCount(3, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertCount(2, $this->repository->getConnections($node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertCount(1, $this->repository->getConnections($node3, array('type' => self::CONNECTION_TYPE)));

        $this->assertContains($connection1, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection2, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection3, $this->repository->getConnections($node1, array('type' => self::CONNECTION_TYPE)));
        $this->assertContains($connection3, $this->repository->getConnections($node3, array('type' => self::CONNECTION_TYPE)));

        $this->assertNotContains($connection3, $this->repository->getConnections($node2, array('type' => self::CONNECTION_TYPE)));
        $this->assertNotContains($connection2, $this->repository->getConnections($node3, array('type' => self::CONNECTION_TYPE)));
    }
}
