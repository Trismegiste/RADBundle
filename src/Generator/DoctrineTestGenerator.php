<?php

namespace Trismegiste\RADBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as SensioGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\Validator\Validator;

/**
 * Generates a test class based on a Doctrine entity.
 *
 */
class DoctrineTestGenerator extends SensioGenerator {

    private $filesystem;
    private $skeletonDir;
    private $className;
    private $classPath;
    protected $testedType = array('integer', 'string', 'datetime', 'float', 'decimal', 'smallint', 'date', 'time', 'text', 'boolean');
    protected $validator = null;

    public function __construct(Filesystem $filesystem, $skeletonDir, Validator $validator) {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
        $this->validator = $validator;
    }

    public function getClassName() {
        return $this->className;
    }

    public function getClassPath() {
        return $this->classPath;
    }

    /**
     * Generates the unit test class if it does not exist.
     *
     * @param BundleInterface $bundle The bundle in which to create the class
     * @param string $entity The entity relative class name
     * @param ClassMetadata $metadata The entity metadata class
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadata $metadata) {
        $parts = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $this->className = $entityClass . 'Test';
        $dirPath = $bundle->getPath() . '/Tests/Model';
        $this->classPath = $dirPath . '/' . str_replace('\\', '/', $entity) . 'Test.php';

        if (file_exists($this->classPath)) {
            throw new \RuntimeException(sprintf('Unable to generate the %s test class as it already exists under the %s file', $this->className, $this->classPath));
        }

        $this->renderFile($this->skeletonDir, 'UnitTest.php.twig', $this->classPath, array(
            'dir' => $this->skeletonDir,
            'fields' => $this->getFieldsFromMetadata($metadata),
            'test_class' => $this->className,
            'entity' => $entityClass,
            'fqcnEntity' => $metadata->getName(),
            'nudeValidation' => $this->getNudeObjectValidationError($metadata->getName()),
        ));
    }

    /**
     * Returns an array of typed fields.
     *
     * @param ClassMetadataInfo $metadata
     * @return array $fields
     */
    private function getFieldsFromMetadata(ClassMetadata $metadata) {
        $fields = array();

        foreach ($metadata->getFieldNames() as $fieldMapping) {
            if (!$metadata->isIdentifier($fieldMapping)) {
                $type = $metadata->getTypeOfField($fieldMapping);
                if (in_array($type, $this->testedType)) {
                    $fields[] = array(
                        'accessor' => Inflector::classify($fieldMapping),
                        'type' => Inflector::classify($type),
                        'property' => $fieldMapping
                    );
                }
            }
        }

        return $fields;
    }

    protected function getNudeObjectValidationError($fqcn) {
        $obj = new $fqcn();
        $verif = array();
        $violation = $this->validator->validate($obj);
        foreach ($violation as $prop) {
            $verif[] = array(
                'property' => $prop->getPropertyPath(),
                'message' => $prop->getMessage(),
                'accessor' => Inflector::classify($prop->getPropertyPath()),
            );
        }
        return $verif;
    }

}
