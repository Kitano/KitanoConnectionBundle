<?php

namespace Kitano\ConnectionBundle\Model;

class Connection
{
    const STATUS_DISCONNECTED = 0;
    const STATUS_CONNECTED = 1;
    
    protected $sourceObjectClass;
    protected $sourceForgeinKey;
    protected $destinationObjectClass;
    protected $destinationForgeinKey;
    
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
    
    public function __constructor()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function getSourceObjectClass()
    {
        return $this->sourceObjectClass;
    }
    
    public function setSourceObjectClass($value) 
    {
        $this->sourceObjectClass = $value;
        
        return $this;
    }
    
    public function getSourceForeignKey()
    {
        return $this->sourceForeignKey;
    }
    
    public function setSourceForeignKey($value) 
    {
        $this->sourceForeignKey = $value;
        
        return $this;
    }
    
    public function getDestinationObjectClass()
    {
        return $this->destinationObjectClass;
    }
    
    public function setDestinationObjectClass($value) 
    {
        $this->destinationObjectClass = $value;
        
        return $this;
    }
    
    public function getDestinationForeignKey()
    {
        return $this->destinationForeignKey;
    }
    
    public function setDestinationForeignKey($value) 
    {
        $this->destinationForeignKey = $value;
        
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
}
