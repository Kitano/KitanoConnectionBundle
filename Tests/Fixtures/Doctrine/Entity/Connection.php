<?php

namespace Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity;

use Kitano\ConnectionBundle\Entity\Connection as BaseConnection;

class Connection extends BaseConnection
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}