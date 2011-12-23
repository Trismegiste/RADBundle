<?php

namespace Trismegiste\RADBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as SensioGenerator;
use Symfony\Component\HttpKernel\Util\Filesystem;

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
    public function generate($collection, $filter) {
        
        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s test class as it already exists under the %s file', $this->className, $this->classPath));
        }

        $this->renderFile($this->skeletonDir, 'RoutingTest.php.twig', $this->classPath, array(
            'fqcnEntity' => $metadata->getName(),
            'nudeValidation' => $this->getNudeObjectValidationError($metadata->getName()),
        ));
    }

}
