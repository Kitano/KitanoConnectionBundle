<?php

namespace Kitano\ConnectionBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Model\NodeInterface;

/**
 * ConnectionRepository
 */
class DoctrineMongoDBConnectionRepository extends DocumentRepository implements ConnectionRepositoryInterface
{
    /**
     * @var string
     */
    protected $class;

    public function __construct(DocumentManager $dm, $class)
    {
        $metadata = $dm->getClassMetadata($class);
        parent::__construct($dm, $dm->getUnitOfWork(), $metadata);

        $this->class = $class;
    }

    /**
     * @param NodeInterface $node
     * @param array         $filters
     *
     * @return array
     */
    public function getConnectionsWithSource(NodeInterface $node, array $filters = array())
    {
        $qb = $this->createQueryBuilder("Connection")
            ->field("source")->references($node)
        ;

        if (array_key_exists('type', $filters)) {
            $qb->field('type')->equals($filters['type']);
        }

        return  $qb->getQuery()->execute()->toArray();
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array                                        $filters
     *
     * @return array
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array())
    {
        $qb = $this->createQueryBuilder('Connection')
            ->field('destination')->references($node)
        ;

        if (array_key_exists("type", $filters)) {
            $qb->field("type")->equals($filters['type']);
        }

        return $qb->getQuery()->execute()->toArray();
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     * @return array
     */
    public function getConnections(NodeInterface $node, array $filters = array())
    {
        $qb = $this->createQueryBuilder('Connection');

        $qb->addOr(
            $qb->expr()
                ->field("source")->references($node)
        )
        ->addOr(
            $qb->expr()
                ->field("destination")->references($node)
        );

        if (array_key_exists('type', $filters)) {
            $qb->field('type')->equals($filters['type']);
        }

        return $qb->getQuery()->execute()->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function areConnected(NodeInterface $nodeA, NodeInterface $nodeB, array $filters = array())
    {
        $qb = $this->createQueryBuilder('Connection')
            ->field("source")->references($nodeA)
            ->field("destination")->references($nodeB)
        ;

        if (array_key_exists('type', $filters)) {
            $qb->field('type')->equals($filters['type']);
        }

        return ($qb->getQuery()->execute()->count() > 0) ? true : false;
    }

    /**
     * @param mixed $connections ArrayCollection|ConnectionInterface
     *
     * @return mixed ArrayCollection|ConnectionInterface
     */
    public function update($connections)
    {
        if($connections instanceof ArrayCollection) {
            foreach($connections as $connection) {
                $this->persistConnection($connection);
            }
        } else {
            $this->persistConnection($connections);
        }

        $this->getDocumentManager()->flush();

        return $connections;
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\ConnectionInterface $connection
     */
    protected function persistConnection(ConnectionInterface $connection)
    {
        $this->getDocumentManager()->persist($connection);
    }

    /**
     * @param mixed $connections ArrayCollection|ConnectionInterface
     * @return DoctrineMongoDBConnectionRepository
     */
    public function destroy($connections)
    {
        if($connections instanceof ArrayCollection) {
            foreach($connections as $connection) {
                $this->removeConnection($connection);
            }
        } else {
            $this->removeConnection($connections);
        }

        $this->getDocumentManager()->flush();

        return $this;
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\ConnectionInterface $connection
     */
    protected function removeConnection(ConnectionInterface $connection)
    {
        $this->getDocumentManager()->remove($connection);
    }

    /**
     * @return ConnectionInterface
     */
    public function createEmptyConnection()
    {
        return new $this->class();
    }
}
