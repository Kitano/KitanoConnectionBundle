<?php

namespace Kitano\ConnectionBundle\Model;

interface ConnectionInterface
{
    const STATUS_DISCONNECTED = 0;
    const STATUS_CONNECTED = 1;

    /**
     * Returns the Node from where the Connection (edge) start
     *
     * @return \Kitano\ConnectionBundle\Model\NodeInterface
     */
    public function getSource();

    /**
     * Sets the Node from where the Connection start
     *
     * @param NodeInterface $node
     */
    public function setSource(NodeInterface $node);

    /**
     * Returns the Node to which the Connection is directed
     *
     * @return \Kitano\ConnectionBundle\Model\NodeInterface
     */
    public function getDestination();

    /**
     * Sets the Node to which the Connection is directed
     *
     * @param NodeInterface $node
     */
    public function setDestination(NodeInterface $node);

    /**
     * Returns the current connection state (i.e: connected, disconnected)
     *
     * @return int
     */
    public function getStatus();

    /**
     * Returns the "type" of this connection (user defined)
     * This type is used to identify the kind of connection which is linking a Node
     * to another (i.e: "like", "follow", ...)
     *
     * @return mixed
     */
    public function getType();

    /**
     * Sets the connection type
     *
     * @param string $type
     */
    public function setType($type);

    /**
     * Returns the last date the Connection was (re)established at
     *
     * @return \DateTime
     */
    public function getConnectedAt();

    /**
     * Returns the date the connection was cancelled at
     *
     * @return \DateTime
     */
    public function getDisconnectedAt();

    /**
     * Returns the date the connection was initially created at
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Establishes the connection from Source (node) to Destination (node)
     *
     * @return void
     */
    public function connect();

    /**
     * Cancels the connection
     *
     * @return void
     */
    public function disconnect();
}