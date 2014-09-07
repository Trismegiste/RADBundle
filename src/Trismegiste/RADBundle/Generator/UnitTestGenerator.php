<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * Generates unit test class based on parsed class
 */
class UnitTestGenerator extends AbstractGenerator
{

    public function generate($bundlePath, $bundleNamespace, $relativeClassname)
    {
        $rootNamespace = explode('\\', $bundleNamespace);
        $code = file_get_contents($bundlePath . '/' . $relativeClassname . '.php');

        $collector = new ClassCollector();
        $info = $collector->collect($code);

        $fqcnTestedClass = $info['namespace'];
        $fqcnTestedClass[] = $info['classname'];
        $fqcnTestedClass = implode('\\', $fqcnTestedClass);

        $namespace4Test = $rootNamespace;
        $namespace4Test[] = 'Tests';
        array_splice($namespace4Test, count($namespace4Test), 0, array_diff_assoc($info['namespace'], $rootNamespace));

        $destPath = $bundlePath . '/Tests/' . $relativeClassname . 'Test.php';

        if (!$this->filesystem->exists($destPath)) {
            $this->renderFile('SmartTest.php.twig', $destPath, [
                'namespace4Test' => implode('\\', $namespace4Test),
                'info' => $info,
                'fqcnTestedClass' => $fqcnTestedClass
            ]);
        } else {
            throw new \InvalidArgumentException("$destPath already exists");
        }
    }

    protected function customizeTwig(\Twig_Environment $twig)
    {
        $twig->addExtension(new TwigExt());
    }

}
