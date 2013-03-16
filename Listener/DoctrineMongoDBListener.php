<?php

namespace Kitano\ConnectionBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Kitano\ConnectionBundle\Model\NodeInterface;

class DoctrineMongoDBListener implements EventSubscriber
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function getSubscribedEvents()
    {
        return array(
            'preRemove'
        );
    }

    /**
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        if($document instanceof NodeInterface)
        {
            if($this->getConnectionManager()->hasConnections($document)) {
                $connections = $this->getConnectionManager()->getConnections($document);

                foreach($connections as $connection) {
                    $eventArgs->getDocumentManager()->remove($connection);
                }

                $eventArgs->getDocumentManager()->flush(); //Necessary
            }
        }
    }

    /**
     *
     * @return \Kitano\ConnectionBundle\Manager\ConnectionManagerInterface
     */
    public function getConnectionManager()
    {
        return $this->container->get('kitano_connection.manager.connection');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
