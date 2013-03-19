<?php

namespace Kitano\ConnectionBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Kitano\ConnectionBundle\Model\ConnectionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface;
use Kitano\ConnectionBundle\Event\ConnectionEvent;
use Kitano\ConnectionBundle\Model\NodeInterface;
use Kitano\ConnectionBundle\Manager\FilterValidator;
use Kitano\ConnectionBundle\Exception\AlreadyConnectedException;

class ConnectionManager implements ConnectionManagerInterface
{
    /**
     * @var ConnectionRepositoryInterface
     */
    protected $connectionRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var FilterValidator
     */
    protected $filterValidator;

    /**
     * {@inheritDoc}
     *
     * @throws AlreadyConnectedException When connection from source to destination already exists
     */
    public function connect(NodeInterface $source, NodeInterface $destination, $type)
    {
        if ($this->areConnected($source, $destination, array('type' => $type))) {
            throw new AlreadyConnectedException(sprintf('Objects %s (%s) and %s (%s) are already connected', get_class($source), $source->getId(), get_class($destination),$destination->getId()));
        }

        $connection = $this->getConnectionRepository()->createEmptyConnection();
        $connection->setSource($source);
        $connection->setDestination($destination);
        $connection->setType($type);

        $this->getConnectionRepository()->update($connection);

        if ($this->dispatcher) {
            $this->dispatcher->dispatch(ConnectionEvent::CONNECTED, new ConnectionEvent(($connection)));
        }

        return $connection;
    }

    /**
     * {@inheritDoc}
     *
     * @return ConnectionManagerInterface
     */
    public function disconnect(ConnectionInterface $connection)
    {
        if ($this->dispatcher) {
            $this->dispatcher->dispatch (ConnectionEvent::DISCONNECTED, new ConnectionEvent(($connection)));
        }

        $this->getConnectionRepository()->destroy($connection);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function areConnected(NodeInterface $source, NodeInterface $destination, array $filters = array())
    {
        $connectionsTo = $this->getConnectionsTo($destination, $filters);
        $connectionsFrom = $this->getConnectionsFrom($source, $filters);

        $areConnected = false;

        foreach ($connectionsFrom as $connectionFrom) {
            if (in_array($connectionFrom, $connectionsTo, true)) {
                $areConnected = true;
                break;
            }
        }

        return $areConnected;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isConnectedTo(NodeInterface $source, NodeInterface $destination, array $filters = array())
    {
        $connectionsTo = $this->getConnectionsTo($destination, $filters);

        $areConnected = false;

        foreach ($connectionsTo as $connectionFrom) {
            if (in_array($connectionFrom, $connectionsTo, true)) {
                $areConnected = true;
                break;
            }
        }

        return $areConnected;
    }

    /**
     * {@inheritDoc}
     */
    public function hasConnections(NodeInterface $node, array $filters = array())
    {
        $this->filterValidator->validateFilters($filters);

        return count($this->getConnections($node, $filters)) > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionsTo(NodeInterface $node, array $filters = array())
    {
        $this->filterValidator->validateFilters($filters);

        return $this->getConnectionRepository()->getConnectionsWithDestination($node, $filters);
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionsFrom(NodeInterface $node, array $filters = array())
    {
        $this->filterValidator->validateFilters($filters);

        return $this->getConnectionRepository()->getConnectionsWithSource($node, $filters);
    }

    /**
     * {@inheritDoc}
     */
    public function getConnections(NodeInterface $node, array $filters = array())
    {
        $this->filterValidator->validateFilters($filters);

        $connectionsFrom = $this->getConnectionsFrom($node, $filters);
        $connectionsTo = $this->getConnectionsTo($node, $filters);

        if (null === $connectionsFrom && null === $connectionsTo) {
            return null;
        } else {
            return new ArrayCollection(array_merge((array) $connectionsFrom, (array) $connectionsTo));
        }
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param ConnectionRepositoryInterface $connectionRepository
     */
    public function setConnectionRepository(ConnectionRepositoryInterface $connectionRepository)
    {
        $this->connectionRepository = $connectionRepository;
    }

    /**
     * @return ConnectionRepositoryInterface $connectionRepository
     */
    public function getConnectionRepository()
    {
        return $this->connectionRepository;
    }

    /**
     * @param \Kitano\ConnectionBundle\Manager\FilterValidator
     */
    public function setFilterValidator(FilterValidator $validator)
    {
        $this->filterValidator = $validator;

        return $this;
    }

    /**
     * @return \Kitano\ConnectionBundle\Manager\FilterValidator
     */
    public function getFilterValidator()
    {
        return $this->filterValidator;
    }
}
