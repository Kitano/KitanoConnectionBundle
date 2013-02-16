<?php

namespace Kitano\ConnectionBundle\Manager;

use Kitano\ConnectionBundle\ConnectionRepositoryInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class ConnectionRepositoryFactory
{
    private $repositories = array();
    private $defaultRepository;

    public function __construct($defaultRepository)
    {
        $this->defaultRepository = $defaultRepository;
    }

    public function getRepository()
    {
        if(!array_key_exists($this->defaultRepository, $this->repositories)) {
            throw new InvalidArgumentException(sprintf('The repository taggued "%s" does not exists.', $this->defaultRepository));
        }

        return $this->repositories[$this->defaultRepository];
    }

    public function addRepository(ConnectionRepositoryInterface $repository, $alias)
    {
        // last entry for the win
        $this->repositories[$alias] = $repository;
    }
}