<?php
/*
 * radbundle
 */

namespace Trismegiste\RADBundle\Generator;

/**
 * Description of UnitTestGenerator
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
    public function dumpCalling($method, $signature)
    {
        $compilParam = [];
        foreach ($signature as $argName => $argInfo) {
            $compilParam[] = '$' . $argName;

            if (strlen($argInfo['type'])) {
                ?>
                $<?= $argName ?> = $this->getMock('<?= $argInfo['type'] ?>'
                <?php if (!empty($argInfo['call'])) : ?>
                    , array(<?php
                    echo implode(array_map(function($val) {
                                        return "'$val'";
                                    }, array_keys($argInfo['call'])))
                    ?>)
                <?php else : ?>
                    , array()
                <?php endif ?>, array(), '', true, true, false);
            <?php } else { ?>
                $<?= $argName ?> = 42;
                <?php
            }
        }
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

}