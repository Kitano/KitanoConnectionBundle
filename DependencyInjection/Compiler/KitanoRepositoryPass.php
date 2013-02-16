<?php

namespace Kitano\ConnectionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;


class KitanoRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->hasDefinition('kitano.connection.repository_factory')) {
            return;
        }

        $definition = $container->getDefinition('kitano.connection.repository_factory');
        $taggedServices = $container->findTaggedServiceIds('kitano_connection.repository');

        foreach($taggedServices as $id => $tagAttributes)
        {
            foreach ($tagAttributes as $key => $attributes)
            {
                if($key == "alias") {
                    $container->getDefinition($id)->addMethodCall('setAlias', array($attributes['alias']));
                }

                $definition->addMethodCall(
                    'addRepository',
                    array(
                        new Reference($id),
                        $attributes["alias"]
                    )
                );
            }
        }
    }
}