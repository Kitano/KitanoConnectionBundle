<?php

namespace Kitano\ConnectionBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineOrmListener implements EventSubscriber
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
        $entity = $eventArgs->getEntity();
        
        if($this->getConnectionManager()->hasConnections($entity)) {
            $connections = $this->getConnectionManager()->getConnections($entity);
            
            foreach($connections as $connection) {
                $eventArgs->getEntityManager()->remove($connection);
            }
            
            $eventArgs->getEntityManager()->flush(); //Necessary
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
