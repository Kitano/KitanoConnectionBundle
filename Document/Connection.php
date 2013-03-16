<?php

namespace Kitano\ConnectionBundle\Document;

use Kitano\ConnectionBundle\Model\Connection as BaseConnection;

class Connection extends BaseConnection
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
