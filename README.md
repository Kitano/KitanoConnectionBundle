ConnectionBundle
================

State : Not working. Work In Progress

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
        $connectionManager = $this->get("kitano.connection.manager");

        $userA = $userRepository->find(1);
        $userB = $bookRepository->find(42);

        // User A wants to "follow" User B activity
        // User A clics "follow" button on User B profile page
        $connectionManager->connect($userA, $userB, 'follow');
    }

    public function newPostAction() 
    {
        $connectionManager = $this->get("kitano.connection.manager");

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
