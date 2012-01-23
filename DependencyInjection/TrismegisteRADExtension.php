<?php

namespace Trismegiste\RADBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TrismegisteRADExtension extends Extension {

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $container
                ->register('routing.extract.ctrl', 'Trismegiste\RADBundle\DependencyInjection\RoutingControllerFilter')
                ->addArgument(new Reference('router'));
        $container
                ->register('routing.extract.name', 'Trismegiste\RADBundle\DependencyInjection\RoutingNameFilter')
                ->addArgument(new Reference('router'));
    }

}
