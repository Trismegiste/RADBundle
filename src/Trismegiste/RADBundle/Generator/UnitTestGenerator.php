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

    public function generate($str, array $rootNamespace = [])
    {
        $collector = new ClassCollector();
        $info = $collector->collect($str);

        $fqcnTestedClass = $info['namespace'];
        $fqcnTestedClass[] = $info['classname'];
        $fqcnTestedClass = implode('\\', $fqcnTestedClass);

        $namespace4Test = $rootNamespace;
        $namespace4Test[] = 'Tests';
        array_splice($namespace4Test, count($namespace4Test), 0, array_diff_assoc($info['namespace'], $rootNamespace));

        $this->renderFile('SmartTest.php.twig', 'a.php', [
            'namespace4Test' => implode('\\', $namespace4Test),
            'info' => $info,
            'fqcnTestedClass' => $fqcnTestedClass
        ]);
    }

    /**
     * rendering the require above
     */
    static public function dumpCalling($method, $signature)
    {
        $compilParam = static::dumpMockParameterForCalling($signature);

        foreach ($signature as $argName => $argInfo) :
            ?>
            <?php if (!empty($argInfo['call'])) : ?>
                <?php foreach ($argInfo['call'] as $oneStub => $cpt) : ?>
                    $<?= $argName ?>->expects($this->exactly(<?= $cpt ?>))->method('<?= $oneStub ?>');
                <?php endforeach ?>
            <?php endif ?>
            <?php
        endforeach;

        return $compilParam;
    }

    static public function dumpMockParameterForCalling($signature, $prefix = '$')
    {
        $compilParam = [];
        foreach ($signature as $argName => $argInfo) {
            $compilParam[] = '$' . $argName;

            if (strlen($argInfo['type'])) {
                ?>
                <?= $prefix . $argName ?> = $this->getMock('<?= $argInfo['type'] ?>'
                <?php if (!empty($argInfo['call'])) : ?>
                    , [<?php
                    echo implode(',', array_map(function($val) {
                                        return "'$val'";
                                    }, array_keys($argInfo['call'])))
                    ?>]
                <?php else : ?>
                    , []
                <?php endif ?>, [], '', false);
            <?php } else { ?>
                $<?= $argName ?> = 42;
                <?php
            }
        }

        return $compilParam;
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
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        return file_put_contents($target, $this->render($template, $parameters));
    }

}
