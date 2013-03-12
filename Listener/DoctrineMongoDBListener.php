<?php

namespace Kitano\ConnectionBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;

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
