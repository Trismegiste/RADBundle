<?php

/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Generates unit test class based on parsed class
 */
class UnitTestGenerator
{

    private $filesystem;
    private $skeletonDirs;

    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->setSkeletonDirs($skeletonDir);
    }

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

    public function setSkeletonDirs($skeletonDirs)
    {
        $this->skeletonDirs = is_array($skeletonDirs) ? $skeletonDirs : array($skeletonDirs);
    }

    protected function render($template, $parameters)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->skeletonDirs), array(
            'debug' => true,
            'cache' => false,
            'strict_variables' => true,
            'autoescape' => false,
        ));
        $twig->addExtension(new TwigExt());

        return $twig->render($template, $parameters);
    }

    protected function renderFile($template, $target, $parameters)
    {
        $this->filesystem->dumpFile($target, $this->render($template, $parameters));
    }

}
