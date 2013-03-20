ConnectionBundle        [![Build Status](https://travis-ci.org/Kitano/KitanoConnectionBundle.png?branch=master)](https://travis-ci.org/Kitano/KitanoConnectionBundle)
=====================================================================================================================================================================

State : Working. Unstable.

Implementation of a directed Graph for establishing connections between objects.

For instance, a connection may represent:
* follower/following relationship between two users on a social network
* an user "following" a "tag"
* in fact, any relation between any root objects which need to be connected for any reason ;-)

The purpose of this bundle is not to get a ready-to-use implementation but at the minimum a code base to ease the integration of such a system in a Symfony 2 application.


Use case
--------

```php
<?php
namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    public function indexAction() 
    {
        $connectionManager = $this->get("kitano_connection.manager.connection");

        $userA = $userRepository->find(1);
        $userB = $bookRepository->find(42);

        // User A wants to "follow" User B activity
        // User A clics "follow" button on User B profile page
        $connectionManager->connect($userA, $userB, 'follow');
    }

    public function newPostAction() 
    {
        $connectionManager = $this->get("kitano_connection.manager.connection");

        $userA = $userRepository->find(1);

        // User B does something like creating a new Post
        // We notify all users connected to B
        $connections = $connectionManager->getConnectionsFrom($userB, array('type' => 'follow'));

        foreach($connections as $connection) {
            // Notify !
        }
    }
}

```

Configuration
-------------

**a) Short functional example**

A simple configuration could be something like that

```yml
kitano_connection:
    persistence:
        type: doctrine_orm
```

Using this configuration, you can use KitanoConnectionBundle as the [Use Case](#use-case) example.

After configuration, don't forget to update your RDBMS schema
``` bash
$ php app/console doctrine:schema:update
```

**b) Custom connection**

If you want a custom connection entity.
```yml
kitano_connection:
    persistence:
        type: doctrine_orm
        managed_class: "Acme\Entity\Connection"
```

In this case, don't forget to define an entity schema for **Acme\Entity\Connection**

**c) Custom connection with a custom peristance layer**

If you want to use a custom repository, use the above configuration.

```yml
kitano_connection:
    persistence:
        type: custom
        managed_class: "Acme\Entity\Connection"
```

Define a service named : **kitano_connection.repository.connection** which implement Kitano\ConnectionBundle\Repository\ConnectionRepositoryInterface

Events
------

Events are availble if you want to hook the system.

```php
namespace Acme\DemoBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Kitano\ConnectionBundle\Event\ConnectionEvent;

class ConnectionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'kitano.connection.event.connected"' => array('onConnected', 0),
            'kitano.connection.event.disconnected"' => array('onDisconnected', 0),
        );
    }

    public function onConnected(ConnectionEvent $event)
    {
        $connection = $event->getConnection();
        // ...
    }

    public function onDisconnected(ConnectionEvent $event)
    {
        $connection = $event->getConnection();
        // ...
    }
}
```

```xml
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <parameters>
        <parameter key="acme.demo.connection_subscriber.class">Acme\DemoBundle\Event\ConnectionSubscriber</parameter>
    </parameters>

    <services>
        <!-- listener -->
        <service id="acme.demo.connection_subscriber" class="%acme.demo.connection_subscriber.class%" public="false">
            <tag name="doctrine.event_subscriber" />
        </service>
    </services>
</container>
```

Limitations
-----------

* This bundle can deal with only one peristance layer. It means that you can't Connect a Document object with an Entity object.

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

Testing
-------

Require PHPUnit, phpunit/DbUnit

```bash
$ php composer.phar update --dev
$ phpunit
```
