<?php

namespace Kitano\ConnectionBundle\Proxy;

use Kitano\ConnectionBundle\Model\Connection as ConnectionModel;

class DoctrineMongoDBConnection extends ConnectionModel
{
    protected $source_object_class;
    protected $source_foreign_key;
    protected $destination_object_class;
    protected $destination_foreign_key;

    public function getSourceObjectClass()
    {
        return $this->source_object_class;
    }

    public function setSourceObjectClass($value)
    {
        $this->source_object_class = $value;

        return $this;
    }

    public function getSourceObjectId()
    {
        return $this->source_foreign_key;
    }

    public function setSourceObjectId($value)
    {
        $this->source_foreign_key = $value;

        return $this;
    }

    public function getDestinationObjectClass()
    {
        return $this->destination_object_class;
    }

    public function setDestinationObjectClass($value)
    {
        $this->destination_object_class = $value;

        return $this;
    }

    public function getDestinationObjectId()
    {
        return $this->destination_foreign_key;
    }

    public function setDestinationObjectId($value)
    {
        $this->destination_foreign_key = $value;

        return $this;
    }
}
