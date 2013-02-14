<?php

namespace Kitano\ConnectionBundle\Listener;

use Kitano\ConnectionBundle\Manager\ConnectionManagerInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;

class DoctrineListener implements  EventSubscriber
{
    /**
     *
     * @var \Kitano\ConnectionBundle\Manager\ConnectionManagerInterface
     */
    protected $connectionManager;


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
        
        if($this->manager->hasConnections($entity)) {
            $connections = $this->manager->getConnections($entity);
            
            foreach($connections as $connection) {
                $eventArgs->getEntityManager()->remove($connection);
            }
            
            $eventArgs->getEntityManager()->flush(); //Necessary
        }
    }
    
    /**
     * 
     * @param \Kitano\ConnectionBundle\Manager\ConnectionManagerInterface $connectionManager
     * @return \Kitano\ConnectionBundle\Listener\DoctrineListener
     */
    public function setConnectionManager(ConnectionManagerInterface $connectionManager)
    {
        $this->connectionManager = $connectionManager;
        
        return $this;
    }
    
    public function getConnectionManager()
    {
        return $this->connectionManager;
    }
}
