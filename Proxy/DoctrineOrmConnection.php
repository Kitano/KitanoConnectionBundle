<?php

namespace Kitano\ConnectionBundle\Proxy;

use Kitano\ConnectionBundle\Model\Connection as ConnectionModel;

class DoctrineOrmConnection extends ConnectionModel
{
    protected $sourceObjectClass;
    protected $sourceObjectId;
    protected $destinationObjectClass;
    protected $destinationObjectId;

    public function getSourceObjectClass()
    {
        return $this->sourceObjectClass;
    }

    public function setSourceObjectClass($value)
    {
        $this->sourceObjectClass = $value;

        return $this;
    }

    public function getSourceObjectId()
    {
        return $this->sourceObjectId;
    }

    public function setSourceObjectId($value)
    {
        $this->sourceObjectId = $value;

        return $this;
    }

    public function getDestinationObjectClass()
    {
        return $this->destinationObjectClass;
    }

    public function setDestinationObjectClass($value)
    {
        $this->destinationObjectClass = $value;

        return $this;
    }

    public function getDestinationObjectId()
    {
        return $this->destinationObjectId;
    }

    public function setDestinationObjectId($value)
    {
        $this->destinationObjectId = $value;

        return $this;
    }
}
