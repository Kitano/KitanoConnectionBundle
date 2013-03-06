<?php

namespace Kitano\ConnectionBundle\Model;

class Connection implements ConnectionInterface
{
    protected $source;
    protected $destination;
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function getSource()
    {
        return $this->source;
    }
    
    public function setSource(NodeInterface $source)
    {
        $this->source = $source;
        
        return $this;
    }
    
    public function getDestination()
    {
        return $this->destination;
    }
    
    public function setDestination(NodeInterface $destination)
    {
        $this->destination = $destination;
        
        return $this;
    }
    
    public function getType() 
    {
        return $this->type;
    }
    
    public function setType($type) 
    {
        $this->type = $type;
        
        return $this;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(\DateTime $value) 
    {
        $this->createdAt = $value;
        
        return $this;
    }
}
