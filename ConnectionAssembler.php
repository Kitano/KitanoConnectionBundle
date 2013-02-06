<?php

namespace Kitano\ConnectionBundle;

use Kitano\ConnectionBundle\DTO\Connection as ConnectionDTO;
use Kitano\ConnectionBundle\Entity\Connection as ConnectionEntity;

/**
 * Description of ConnectionAssembler
 *
 * @author thomastourlourat
 */
class ConnectionAssembler 
{
    public function getModel(ConnectionDTO $connectionDTO)
    {
        
    }
    
    public function getDTO(ConnectionEntity $connectionEntity)
    {
        $sourceClassMetadata = $this->_em->getClassMetadata(get_class($source));
        $sourceForeignKey = $sourceClassMetadata->getIdentifierValues($source);
        $sourceObjectClass = $sourceClassMetadata->getName();
        
        $classMetadataDestination = $this->_em->getClassMetadata(get_class($destination));
        $destinationForeignKey = $classMetadataDestination->getIdentifierValues($destination);
        $destinationObjectClass = $classMetadataDestination->getName();
        
        $connection = new Connection();
        $connection->setSourceForeignKey($value);
        $connection->setSourceObjectClass($value);
        $connection->setDestinationForeignKey($value);
        $connection->setDestinationObjectClass($value);
        $connection->connect();
    }
}
