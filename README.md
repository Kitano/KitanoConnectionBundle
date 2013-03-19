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
------------

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

If you want a custom connection system
```yml
kitano_connection:
    persistence:
        type: doctrine_orm
        managed_class: "Acme\Entity\Connection"
```

In this case, don't forget to define an entity schema for **Acme\Entity\Connection**

If you want to use a custom repository, use the above configuration and define a service named : **kitano_connection.repository.connection**
```yml
kitano_connection:
    persistence:
        type: custom
        managed_class: "Acme\Entity\Connection"
```

Events
------

Events are availble if you want to hook the system.


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
