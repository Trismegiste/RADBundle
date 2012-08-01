<?php

namespace Trismegiste\RADBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class TrismegisteRADBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        $container
                ->register('routing.extract.ctrl', __NAMESPACE__ . '\DependencyInjection\RoutingControllerFilter')
                ->addArgument(new Reference('router'));
        $container
                ->register('routing.extract.name', __NAMESPACE__ . '\DependencyInjection\RoutingNameFilter')
                ->addArgument(new Reference('router'));
    }

}
