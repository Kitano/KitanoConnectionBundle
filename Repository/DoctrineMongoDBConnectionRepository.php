<?php

namespace Kitano\ConnectionBundle\Repository;

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
     * @param ConnectionInterface $connection
     *
     * @return ConnectionInterface
     */
    public function update(ConnectionInterface $connection)
    {
        $this->getDocumentManager()->persist($connection);
        $this->getDocumentManager()->flush();

        return $connection;
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return ConnectionRepositoryInterface
     */
    public function destroy(ConnectionInterface $connection)
    {
        $this->getDocumentManager()->remove($connection);
        $this->getDocumentManager()->flush();

        return $this;
    }

    /**
     * @return ConnectionInterface
     */
    public function createEmptyConnection()
    {
        return new $this->class();
    }
}
