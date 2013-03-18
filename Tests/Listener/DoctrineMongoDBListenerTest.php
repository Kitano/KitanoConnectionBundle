<?php

namespace Kitano\ConnectionBundle\Tests\Listener;

use Kitano\ConnectionBundle\Tests\MongoDBTestCase;
use Kitano\ConnectionBundle\Repository\DoctrineMongoDBConnectionRepository;
use Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Document\Node;
use Kitano\ConnectionBundle\Manager\ConnectionManager;
use Kitano\ConnectionBundle\Listener\DoctrineMongoDBListener;

class DoctrineMongoDBListenerTest extends MongoDBTestCase
{
    const CONNECTION_CLASS = 'Kitano\ConnectionBundle\Document\Connection';
    const CONNECTION_TYPE = 'like';

    /**
     * @var \Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface
     */
    private $repository;

    /**
     * @var \Kitano\ConnectionBundle\Manager\ConnectionManagerInterface
     */
    private $manager;

    /**
     * @var \Kitano\ConnectionBundle\Model\NodeInterface
     */
    private $node1;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = new DoctrineMongoDBConnectionRepository($this->getDocumentManager(), static::CONNECTION_CLASS);
        $this->manager = new ConnectionManager();
        $this->manager->setConnectionRepository($this->repository);
        $this->manager->setFilterValidator($this->getFilterValidatorMock());

        $listener = new DoctrineMongoDBListener();
        $listener->setContainer($this->getContainerMock());
        $this->getDocumentManager()->getEventManager()->addEventListener(array("preRemove"), $listener);

        $this->initState();
    }

    protected function initState()
    {
        $this->node1 = new Node(new \MongoId());
        $node2 = new Node(new \MongoId());

        $this->getDocumentManager()->persist($this->node1);
        $this->getDocumentManager()->persist($node2);
        $this->getDocumentManager()->flush();

        $connection = $this->repository->createEmptyConnection();
        $connection->setSource($this->node1);
        $connection->setDestination($node2);
        $connection->setType(self::CONNECTION_TYPE);

        $this->repository->update($connection);
    }

    protected function getContainerMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('get')
            ->with('kitano_connection.manager.connection')
            ->will($this->returnValue($this->manager))
        ;

        return $mock;
    }

    protected function getFilterValidatorMock()
    {
        $mock = $this->getMockBuilder('Kitano\ConnectionBundle\Manager\FilterValidator')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('validateValue')
            ->will($this->returnValue(array()))
        ;

        return $mock;
    }

    public function testGetSubscribedEvents()
    {
        $listener = new DoctrineMongoDBListener();
        $events = $listener->getSubscribedEvents();

        $this->assertTrue(in_array('preRemove', $events));
    }

    public function testPreRemove()
    {
        $this->documentManager->remove($this->node1);
        $this->documentManager->flush();

        $this->assertCount(0, $this->manager->getConnections($this->node1));
    }
}
