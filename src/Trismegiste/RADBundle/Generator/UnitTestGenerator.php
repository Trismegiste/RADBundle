<?php
/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * Generates unit test class based on parsed class
 */
class UnitTestGenerator
{

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

        ob_start();
        require(__DIR__ . '/../Resources/skeleton/test/SmartTest.php');
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
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

}
