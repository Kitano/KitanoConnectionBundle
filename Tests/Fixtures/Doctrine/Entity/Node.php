<?php

namespace Kitano\ConnectionBundle\Tests\Fixtures\Doctrine\Entity;

use Kitano\ConnectionBundle\Model\NodeInterface;

class Node implements NodeInterface
{
    /**
     * @var int
     */
    private $id;

    public function __construct($id = null)
    {
        $this->setId($id);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }
}
