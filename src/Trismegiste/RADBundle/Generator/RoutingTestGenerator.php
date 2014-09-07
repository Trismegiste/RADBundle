<?php

namespace Trismegiste\RADBundle\Generator;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Generates a functional test class based on routes
 */
class RoutingTestGenerator extends AbstractGenerator
{

    /**
     * Generates the routing test class if it does not exist.
     *
     * @param array $collection
     * @param string $filter
     */
    public function generate($routeCollection, BundleInterface $bundle, $className)
    {
        $filePath = sprintf("%s/Tests/Controller/%s.php", $bundle->getPath(), $className);

        if (file_exists($filePath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s test class as it already exists', $filePath));
        }

        $this->renderFile('RoutingTest.php.twig', $filePath, array(
            'testClassName' => $className,
            'routes' => $routeCollection,
            'bundleNamespace' => $bundle->getNamespace()));
    }

}
