<?php

namespace Kitano\ConnectionBundle\DTO;

class Connection
{
    const STATUS_DISCONNECTED = 0;
    const STATUS_CONNECTED = 1;
    
    protected $source;
    protected $destination;
    
    /**
     * @var int
     */
    protected $status;
    
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
    
    public function getSource()
    {
        return $this->source;
    }
    
    public function setSource($value) 
    {
        $this->source= $value;
        
        return $this;
    }
    
    public function getDestination()
    {
        return $this->destination;
    }
    
    public function setDestination($value) 
    {
        $this->destination= $value;
        
        return $this;
    }
    
    public function getStatus() 
    {
        return $this->status;
    }
    
    public function setStatus($value) 
    {
        switch($value) {
            case self::STATUS_DISCONNECTED :
                $this->disconnect();
                break;
            case self::STATUS_CONNECTED :
                $this->connect();
                break;
            default :
                throw new \InvalidArgumentException(sprintf('"%s" is not a valid status.', $value));
        }
        
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
    
    public function isConnected ()
    {
        return $this->status === self::STATUS_CONNECTED;
    }
}
