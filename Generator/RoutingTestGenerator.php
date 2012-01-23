<?php

namespace Trismegiste\RADBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as SensioGenerator;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Generates a test class based on a Doctrine entity.
 *
 */
class RoutingTestGenerator extends SensioGenerator {

    private $filesystem;
    private $skeletonDir;

    public function __construct(Filesystem $filesystem, $skeletonDir) {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
    }

    /**
     * Generates the routing test class if it does not exist.
     *
     * @param array $collection
     * @param string $filter
     */
    public function generate($routeCollection, BundleInterface $bundle, $className) {
        $filePath = sprintf("%s/Tests/Controller/%s.php", $bundle->getPath(), $className);

        if (file_exists($filePath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s test class as it already exists', $filePath));
        }

        $this->renderFile($this->skeletonDir, 'RoutingTest.php.twig', $filePath, array(
            'testClassName' => $className,
            'routes' => $routeCollection,
            'bundleNamespace' => $bundle->getNamespace()));
    }

}
