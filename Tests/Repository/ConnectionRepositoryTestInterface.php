<?php

namespace Kitano\ConnectionBundle\Tests\Repository;

interface ConnectionRepositoryTestInterface
{
    public function testCreateEmptyConnectionReturn();
    public function testUpdate();
    public function testDestroy();
    public function testGetConnections();
    public function testGetConnectionsWithSource();
    public function testGetConnectionsWithSourceNotContains();
    public function testGetConnectionsWithDestination();
    public function testGetConnectionsWithDestinationNotContains();
}
