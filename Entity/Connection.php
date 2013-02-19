<?php

namespace Kitano\ConnectionBundle\Entity;

use Kitano\ConnectionBundle\Proxy\DoctrineOrmConnection;

class Connection extends DoctrineOrmConnection
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
