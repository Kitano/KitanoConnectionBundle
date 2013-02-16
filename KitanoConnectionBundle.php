<?php

namespace Kitano\ConnectionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Kitano\ConnectionBundle\DependencyInjection\Compiler\KitanoRepositoryPass;

class KitanoConnectionBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new KitanoRepositoryPass());
    }
}
