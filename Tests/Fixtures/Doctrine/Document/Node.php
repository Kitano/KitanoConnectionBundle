<?php

namespace Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Document;

use Kitano\ConnectionBundle\Model\NodeInterface;

class Node implements NodeInterface
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}