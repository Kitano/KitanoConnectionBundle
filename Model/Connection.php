<?php

namespace Kitano\ConnectionBundle\Model;

class Connection implements ConnectionInterface
{
    protected $source;
    protected $destination;
    
    /**
     * @var int
     */
    protected $status;
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $disconnectedAt;

    /**
     * @var \DateTime
     */
    protected $connectedAt;
    
    public function __constructor()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function getSource()
    {
        return $this->source;
    }
    
    public function setSource(NodeInterface $source)
    {
        $this->source= $source;
        
        return $this;
    }
    
    public function getDestination()
    {
        return $this->destination;
    }
    
    public function setDestination(NodeInterface $destination)
    {
        $this->destination= $destination;
        
        return $this;
    }
    
    public function getStatus() 
    {
        return $this->status;
    }
    
    public function setStatus($status) 
    {
        switch($status) {
            case self::STATUS_DISCONNECTED:
                $this->disconnect();
                break;
            case self::STATUS_CONNECTED:
                $this->connect();
                break;
            default :
                throw new \InvalidArgumentException(sprintf('"%s" is not a valid status.', $status));
        }
        
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
    
    public function getDisconnectedAt()
    {
        return $this->disconnectedAt;
    }
    
    public function setDisconnectedAt(\DateTime $value) 
    {
        $this->disconnectedAt = $value;
        
        return $this;
    }
    
    public function getConnectedAt()
    {
        return $this->connectedAt;
    }
    
    public function setConnectedAt(\DateTime $value) 
    {
        $this->connectedAt = $value;
        
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
    
    public function disconnect()
    {
        $this->status = self::STATUS_DISCONNECTED;
        $this->disconnectedAt = new \DateTime();
        
        return $this;
    }

    public function connect()
    {
        $this->status = self::STATUS_CONNECTED;
        $this->connectedAt = new \DateTime();
        
        return $this;
    }
}
