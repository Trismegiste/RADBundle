<?php

/**
 * RADBundle
 */

namespace Trismegiste\RADBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * TrismegisteRAD Extension
 */
class TrismegisteRADExtension extends Extension
{

    public function load(array $config, \Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

}