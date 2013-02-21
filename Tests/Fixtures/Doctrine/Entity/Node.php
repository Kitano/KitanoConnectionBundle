<?php

namespace Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity;

use Kitano\ConnectionBundle\Model\NodeInterface;

class Node implements NodeInterface
{
    private $id;
    
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}