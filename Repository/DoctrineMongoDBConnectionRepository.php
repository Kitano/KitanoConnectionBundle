<?php

namespace Kitano\ConnectionBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Kitano\ConnectionBundle\Proxy\DoctrineMongoDBConnection;
use Kitano\ConnectionBundle\Model\NodeInterface;
use Kitano\ConnectionBundle\Exception\NotSupportedNodeException;

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
        $objectInformations = $this->extractMetadata($node);

        $objectClass = $objectInformations["object_class"];
        $objectId = $objectInformations["object_id"];

        $qb = $this->createQueryBuilder("Connection")
            ->field('source_object_class')->equals($objectClass)
            ->field('source_foreign_key')->equals($objectId)
        ;

        if(array_key_exists('type', $filters)) {
            $qb->field('type')->equals($filters['type']);
        }

        $connections = $qb->getQuery()->toArray();

        foreach($connections as $connection) {
            $this->fillConnection($connection);
        }

        return $connections;
    }

    /**
     * @param \Kitano\ConnectionBundle\Model\NodeInterface $node
     * @param array $filters
     *
     * @return array
     */
    public function getConnectionsWithDestination(NodeInterface $node, array $filters = array())
    {
        $objectInformations = $this->extractMetadata($node);

        $objectClass = $objectInformations["object_class"];
        $objectId = $objectInformations["object_id"];

        $qb = $this->createQueryBuilder("Connection")
            ->field('destination_object_class')->equals($objectClass)
            ->field('destination_foreign_key')->equals($objectId)
        ;

        if(array_key_exists('type', $filters)) {
            $qb->field('type')->equals($filters['type']);
        }

        $connections = $qb->getQuery()->toArray();

        foreach($connections as $connection) {
            $this->fillConnection($connection);
        }

        return $connections;
    }

    /**
     * @param ConnectionInterface $connection
     *
     * @return ConnectionInterface
     */
    public function update(ConnectionInterface $connection)
    {
        $sourceInformations = $this->extractMetadata($connection->getSource());
        $destinationInformations = $this->extractMetadata($connection->getDestination());

        $connection
            ->setSourceObjectId($sourceInformations["object_id"])
            ->setSourceObjectClass($sourceInformations["object_class"])
            ->setDestinationObjectId($destinationInformations["object_id"])
            ->setDestinationObjectClass($destinationInformations["object_class"])
        ;

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

    /**
     * @param NodeInterface $node
     *
     * @return array
     */
    protected function extractMetadata(NodeInterface $node)
    {
        $classMetadata = $this->getDocumentManager()->getClassMetadata(get_class($node));
        $ids = $classMetadata->getIdentifierValues($node);

        if(count($ids) > 1) {
            throw new NotSupportedNodeException("Composed primary keys for: " . $classMetadata->getName());
        }

        return array(
            'object_class' => $classMetadata->getName(),
            'object_id' => array_pop($ids),
        );
    }

    /**
     * @param DoctrineOrmConnection $connection
     *
     * @return DoctrineOrmConnection
     */
    protected function fillConnection(DoctrineMongoDBConnection $connection)
    {
        $source = $this->getDocumentManager()->getRepository($connection->getSourceObjectClass())->find($connection->getSourceObjectId());
        $destination = $this->getDocumentManager()->getRepository($connection->getDestinationObjectClass())->find($connection->getDestinationObjectId());

        $connection->setSource($source);
        $connection->setDestination($destination);

        return $connection;
    }
}
