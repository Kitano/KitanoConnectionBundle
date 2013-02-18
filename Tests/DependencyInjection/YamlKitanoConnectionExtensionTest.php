<?php

namespace Kitano\ConnectionBundle\Tests\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class YamlKitanoConnectionExtensionTest extends KitanoConnectionExtensionTest
{
    protected function loadFromFile(ContainerBuilder $container, $file)
    {
        $loadXml = new YamlFileLoader($container, new FileLocator(__DIR__.'/Fixtures/yml'));
        $loadXml->load($file.'.yml');
    }
}
