<?php

namespace Kitano\ConnectionBundle\Document;

use Kitano\ConnectionBundle\Proxy\DoctrineMongoDBConnection;

class Connection extends DoctrineMongoDBConnection
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
